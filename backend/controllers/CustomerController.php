<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;
use yii\base\Model;
use backend\form\CustomerSearch;
use common\models\Customer;
use common\models\CustomerProfile;
use common\models\CustomerEmail;

class CustomerController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return array_merge($behaviors, [
           'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ]
            ],
            'ajax' => [
                'class' => AjaxFilter::className(),
                'only' => ['view'],
            ],
        ]);
    }

    public function actionIndex()
    {
        $search = new CustomerSearch();
        $provider = $search->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel'  => $search,
            'dataProvider' => $provider,
        ]);
    }


    public function actionCreate()
    {
        $customer = new Customer(['scenario' => Customer::SCENARIO_CREATE]);
        $profile = new CustomerProfile();
        $email = CustomerEmail::instance();

        $request = Yii::$app->request;

        if($request->isPost) {
            $post = $request->post();
            if($customer->load($post) && $profile->load($post) && $email->load($post)) {
                if(Model::validateMultiple([$customer, $profile, $email])) {
                    Customer::getDb()->transaction(function() use ($customer, $profile, $email) {
                        $customer->save(false);
                        $profile->setCustomer($customer);
                        $profile->save(false);
                        $email->setPrimaryCustomer($customer);
                        $email->save(false);
                    });
                    return $this->redirect(['index']);
                }
            }
        }
        $customer->cleanPassword();
        return $this->render('edit', compact('customer', 'profile', 'email'));
    }

    public function actionView($id)
    {
        $customer = Customer::find()->where(['id' => $id])
           -> with('emails', 'profile')
           -> one();
        if(!$customer) {
            throw new \yii\web\NotFoundHttpException('page not found');
        }
        return $this->asJson($customer->toArray());
    }




    public function actionUpdate($id)
    {
        $customer = Customer::find()->where(['id' => $id])
           -> with('primaryEmail', 'profile')
           -> one();
        if(!$customer) {
            throw new \yii\web\NotFoundHttpException('page not found');
        }
        $email = $customer->primaryEmail;
        $profile = $customer->profile;

        return $this->render('edit', compact('customer', 'profile', 'email'));       
    }

    public function actionDelete($id)
    {

    }

}