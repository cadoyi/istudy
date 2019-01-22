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
        $behaviors = parent::behaviors();
        if($login = $this->login()) {
            $behaviors['login'] = $login;
        }

        if($rbac = $this->rbac()) {
            $behaviors['rbac'] = $rbac;
        }

        return $behaviors;
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

    
    /**
     * 查找模型
     * 
     * @param  integer $id        模型的 id 字段
     * @param  string  $modelClass 模型的完全类名
     * @throws yii\web\NotFoundHttpException 
     * @return $object
     */
    public function findModel($id, $modelClass)
    {
         $model = $modelClass::findOne($id);
         if(!$model instanceof $modelClass) {
            throw new NotFoundHttpException(Yii::t('admin', 'Page not found'));
         }
         return $model;
    }


    public function renderView($model)
    {
        $data = $model->toArray();
        $result = [];
        foreach($data as $name => $value) {
            $label = $model->getAttributeLabel($name);
            $result[$label] = $value;
        }
        return $this->asJson($result);
    }


}