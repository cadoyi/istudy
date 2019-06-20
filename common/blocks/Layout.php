<?php

namespace common\blocks;

use Yii;

/**
 * 视图 layout 的 block
 * 
 */
class Layout extends Block
{


    protected $bodyClass = [];


    public function init()
    {
        parent::init();
        $this->initBodyClass();
    }



    /**
     * 初始化 body 类.
     * 
     */
    protected function initBodyClass()
    {
        if(empty($this->bodyClass)) {
            $class = str_replace('/', '-', Yii::$app->controller->route);
            $this->bodyClass[$class] = true;
        }
    }


    /**
     * 获取 body 的 css 类
     * 
     * @return string
     */
    public function getBodyClass()
    {
         return implode(' ', array_keys($this->bodyClass));
    }

    
    /**
     * 设置 body class , 会覆盖之前的
     * 
     * @param string|array $class
     */
    public function setBodyClass($class)
    {
        $this->bodyClass = [];
        $this->initBodyClass();
        $this->addBodyClass($class);
    }


    /**
     * 增加 body 的 class
     * 
     * @param string|array $class
     */
    public function addBodyClass($classes)
    {
        $_class = [];
        if(!is_array($classes)) {
            $classes = [$classes];
        }
        foreach($classes as $class) {
            if(strpos($class, ' ') === false) {
                $_class[$class] = true;
            }else {
                $_classes = preg_split('/\s+/', $class);
                foreach($_classes as $final) {
                    $_class[$final] = true;
                }
            }
        }
        $this->bodyClass = array_merge($this->bodyClass, $_class);
        return $this;
    }    

}