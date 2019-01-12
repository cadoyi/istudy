<?php

namespace backend\controllers;

use Yii;
use common\base\WebController;
use yii\filters\AccessControl;

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
        $self = [
            'login' => [
                'class' => AccessControl::className(),
                'rules' => [
                    'must' => [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
        $behaviors = array_merge($parent, $self);
        $childBehaviors = $this->haviors($behaviors);
        $child = is_array($childBehaviors) ? $childBehaviors : [];
        return array_merge($behaviors, $child);
    }




    /**
     * 这是一个 behaviors 的回调,让子类可以有参数,也可以没有参数. 
     * 如果这个方法返回一个数组, 那么会直接和 $behaviors 合并.
     * 
     * @param  array &$behaviors - behaviors() 方法中定义的数组.
     * @return mixed 
     */
    public function haviors(&$behaviors)
    {
        
    }



}