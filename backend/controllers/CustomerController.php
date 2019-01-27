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
        return array_merge(parent::behaviors(), [
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
        $customer = new Customer([
            'scenario' => Customer::SCENARIO_CREATE,
        ]);
        $profile = new CustomerProfile([
            'scenario' => CustomerProfile::SCENARIO_CREATE,
        ]);

        $email = new CustomerEmail([
            'scenario' => CustomerEmail::SCENARIO_CREATE,
        ]);


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
           -> with('emails', 'profile')
           -> one();
        if(!$customer) {
            throw new \yii\web\NotFoundHttpException('page not found');
        }
        $emails = $customer->emails;
        $profile = $customer->profile;
        $customer->scenario = Customer::SCENARIO_UPDATE;
        foreach($emails as $email) {
            $email->scenario = CustomerEmail::SCENARIO_UPDATE;
        }
        $profile->scenario = CustomerProfile::SCENARIO_UPDATE;
        $request = Yii::$app->request;
        if($request->isPost) {
            $post = $request->post();
            $customer->load($post);
            $profile->load($post);
            foreach($post['emails'] as $index => $data) {
                $email = $emails[$index];
                $email->load($data, '');
            }
            if($customer->validate() && $profile->validate() && Model::validateMultiple($emails)) {
                Customer::getDb()->transaction(function() use ($customer, $profile, $emails) {
                    $customer->save(false);
                    $profile->save(false);
                    foreach($emails as $email) {
                        $email->save(false);
                    }
                });
                $this->redirect(['index']);
            }
        }
        $customer->cleanPassword();
        return $this->render('edit', compact('customer', 'profile', 'emails'));       
    }

    public function actionDelete($id)
    {
        $customer = Customer::find()->where(['id' => $id])
          -> with('emails', 'profile')
          -> one();
        if(!$customer) {
            throw new \yii\web\NotFoundHttpException('page not found');
        }
        Customer::getDb()->transaction(function() use ($customer) {
            foreach($customer->emails as $email) {
                $email->delete();
            }
            $customer->profile->delete();
            $customer->delete();
        });
        return $this->redirect(['index']);
    }

}