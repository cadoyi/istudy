<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\base\Model;
use common\models\Customer;
use core\exception\ValidateException;
use frontend\models\CustomerPasswordForm;

class CustomerController extends Controller
{

    public $layout = 'personal';

    public function behaviors()
    {
        return [
           'login' => [
               'class' => AccessControl::className(),
               'ruleConfig' => [
                   'class' => 'yii\filters\AccessRule',
                   'allow' => true,
               ],
               'rules' => [
                   [
                       'roles' => ['@'],
                   ],
               ],
           ],
        ];
    }

    /**
     * 账号信息
     * 
     */
	public function actionIndex()
	{
		$this->_title('Personal');
        $customer = Yii::$app->user->identity;
        $profile = $customer->profile;
        $customer->scenario = $profile->scenario = Customer::SCENARIO_UPDATE;

        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if($this->updateCustomer($post, [$customer, $profile])) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Customer updated'));
                return $this->refresh();
            }
        }
        return $this->render('index', [
           'customer' => $customer,
           'profile'  => $profile,
        ]);
	}


    public function actionPassword()
    {
        $this->_title('Change password');
        $model = new CustomerPasswordForm();        
        if($model->load(Yii::$app->request->post()) && $model->updatePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Password updated'));
            return $this->refresh();
        }
        return $this->render('password', [
            'model' => $model,
        ]);
    }


    public function actionProfile()
    {
        $customer = Yii::$app->user->identity;
        $profile = $customer->profile;
        $customer->scenario = $profile->scenario = Customer::SCENARIO_UPDATE;
        if(Yii::$app->request->isPost) {
            if($this->updateCustomer(Yii::$app->request->post(), [$customer, $profile])) {
                Yii::$app->session->setFlash('success', 'Customer updated');
                return $this->refresh();
            }
        }
        return $this->render('profile', [
            'customer' => $customer,
            'profile' => $profile,
        ]);        
    }

    public function actionContact()
    {
        $customer = Yii::$app->user->identity;
        $profile = $customer->profile;
        $customer->scenario = $profile->scenario = Customer::SCENARIO_UPDATE;
        if(Yii::$app->request->isPost) {
            if($this->updateCustomer(Yii::$app->request->post(), [$customer, $profile])) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Customer updated'));
                return $this->refresh();
            }
        }
        return $this->render('contact', [
            'customer' => $customer,
            'profile'  => $profile,
        ]);
    }


    public function actionFavorite()
    {
        return $this->render('favorite');
    }

    public function actionNotification()
    {
        return $this->render('notification');
    }

    public function updateCustomer($post, $models = [])
    {
        $success = true;
        try {
            $_models = [];
            foreach($models as $index => $model) {
                $model->scenario = Customer::SCENARIO_UPDATE;
                if(!$model->load($post)) {
                    continue;
                }
                $_models[$index] = $model;
            }
            if(empty($_models)) {
                return true;
            }
            if(!Model::validateMultiple($_models)) {
                throw new ValidateException('validate faild');
            }
            Customer::getDb()->transaction(function() use ($_models) {
                foreach($_models as $model) {
                    $model->save(false);
                }
            });
        } catch(ValidateException $e) {
            $success = false;
        }
        return $success;
    }
}