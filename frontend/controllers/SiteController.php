<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Customer;
use core\exception\ConfirmException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 4,
                'maxLength' => 4,
            ],
            'captcha-register' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 4,
                'maxLength' => 4,
            ],
            'captcha-reset-password' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 4,
                'maxLength' => 4,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                if($model->login()) {
                    return $this->goBack();
                }
            } catch(ConfirmException $e) {
                return $this->redirect(['verify', 'id' => $model->user->id]);
            }
        }
        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
        
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->redirect(['verify', 'id' => $user->id]);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionVerify($id)
    {
        $customer = Customer::find()
            -> where([
                'id' => $id, 
                'is_active' => 0
            ])
            -> one();
        if($customer && !$customer->is_active) {
            return $this->render('verify', ['customer' => $customer]);
        }
        throw new BadRequestHttpException('Bad request');
    }

    public function actionConfirmEmail($token, $sequeue, $secret)
    {
        $customer = Customer::find()
           ->where([
              'id'       => $sequeue,
              'auth_key' => $token,
          ])->one();
        $cache = Yii::$app->cache;
        if($customer && !$customer->is_active && $cache->get([$customer->email, $customer->id])) {
            $customer->is_active = 1;
            $customer->save(false);
            return $this->render('confirm-success');
        }
        throw new BadRequestHttpException('Token expired');
    }

    public function actionConfirmResent($id)
    {
        $request = Yii::$app->request;
        $data['success'] = 0;
        if($request->isPost && $request->isAjax) {
            $customer = Customer::find()->where(['id' => $id])->one();
            if($customer && !$customer->is_active) {
                SignupForm::sendRegisterVerifyEmail($customer);
                $data['success'] = 1;
            }
        }
        return $this->asJson($data);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $customer = $model->customer;
            PasswordResetRequestForm::sendEmail($customer);
            return $this->redirect(['verify-password', 'id' => $customer->id]);
        }
        
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionVerifyPassword($id)
    {
        $customer = Customer::find()
            -> where([
                'id' => $id, 
                'is_active' => 1
            ])
            -> one();
        if($customer && $customer->is_active) {
            return $this->render('verify-password', ['customer' => $customer]);
        }
        throw new BadRequestHttpException('Bad request');        
    }

    public function actionConfirmResentPassword($id)
    {
        $request = Yii::$app->request;
        $data['success'] = 0;
        if($request->isPost && $request->isAjax) {
            $customer = Customer::find()->where(['id' => $id, 'is_active' => 1])->one();
            if($customer && Yii::$app->cache->get([$customer->id, 'resetPassword'])) {
                PasswordResetRequestForm::sendEmail($customer);
                $data['success'] = 1;
            }
        }
        return $this->asJson($data);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token, $id)
    {
        $customer = Customer::findIdentity($id);
        if(!$customer || !$customer->validatePasswordResetToken($token)) {
            throw new BadRequestHttpException('Bad request');
        }
        $model = new ResetPasswordForm();
        $model->customer = $customer;
        if($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'password reset successfull');
            return $this->redirect(['login']);
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
