<?php

namespace common\components;

use Yii;
use yii\base\Model;
use common\components\StaticInstanceTrait;


/**
 * 组合 load 和验证.
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
class ComposeModel extends Component
{

    use StaticInstanceTrait;


    /**
     * @var array 模型配置
     */
    public $models = [];


    /**
     * @var array load的时候的表单名.
     */
    public $formNames = [];



    /**
     * @inheritdoc
     * 
     */
    public function init()
    {
        parent::init();
        foreach($this->models as $name => $model) {
            if(!$model instanceof Model) {
                $this->models[$name] = Yii::createObject($model);
            }
        }
    }


    /**
     * 
     * @param  [type] $name  [description]
     * @param  [type] $model [description]
     * @return [type]        [description]
     */
    public function modelLoad($name, $model, $params)
    {
        if(isset($this->formNames[$name]) || array_key_exists($name, $this->formNames)) {
            $formName = $this->formNames[$name];
        } else {
            $formName = '';
        }
        return $model->load($params, $formName);
    }


    /**
     * 加载模型.
     * 
     * @param  array  $params 请求参数
     * @return boolean
     */
    public function load(array $params = [])
    {
        $result = true;
        foreach($this->models as $name => $model) {
            if(false === $this->modelLoad($name, $model, $params)) {
                $result = false;
            }
        }
        return $result;
    }


    /**
     * 验证模型
     * 
     * @return boolean
     */
    public function validate()
    {
        $result = true;
        foreach($this->models as $name => $model) {
            if(!$model->validate()) {
                $result = false;
            }
        }
        return $result;
    }


    /**
     * 加载数据并验证.
     * 
     * @param  array  $params 请求参数
     * @return boolean
     */
    public function loadAndValidate(array $params)
    {
        return $this->load($params) && $this->validate();
    }


}