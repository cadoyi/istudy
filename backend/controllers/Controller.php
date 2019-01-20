<?php

namespace backend\controllers;

use Yii;
use common\base\WebController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class Controller extends WebController
{


    /**
     * 设置一个统一 behaviors, 让子类可以不需要考虑父类的设计.
     * 
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();
        $child = [];
        if($login = $this->login()) {
            $child['login'] = $login;
        }

        if($rbac = $this->rbac()) {
            $child['rbac'] = $rbac;
        }

        return array_merge($parent, $child);
    }


    /**
     * 设置 login 的 behaviors
     * 
     * @return array 
     */
    public function login()
    {
        return [
            'class' => AccessControl::className(),
            'denyCallback' => function($rule, $action) {
                if(Yii::$app->user->isGuest) { 
                    if(Yii::$app->request->isGet) {
                        $route = [ $this->route ];
                        $params = $this->actionParams;
                        $url = array_merge($route, $params);
                        Yii::$app->user->setReturnUrl($url);
                    }
                    return Yii::$app->user->loginRequired();
                }
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            },
            'rules' => [
                'must' => [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
    }


    /**
     * 设置 rbac 的 behaviors
     * 
     * @return array
     */
    public function rbac()
    {
        return [];
    }


    public function findModel($id, $modelClass)
    {
         $model = $modelClass::findOne($id);
         if(!$model instanceof $modelClass) {
            throw new NotFoundHttpException(Yii::t('admin', 'Page not found'));
         }
         return $model;
    }


}