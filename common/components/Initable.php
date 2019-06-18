<?php

namespace common\components;

use Yii;

/**
 * 有些东西是需要在组件 init 之后,执行其他方法之前,立即被调用的.
 * 因此,在应用启动之前启动这个组件,这个组件调用应用组件,然后执行处理器.
 *
 * 比如:
 *    flysystem 要附加插件,插件提供了一个方法,这个方法如果直接使用 behaviors ,
 *    那么在 attach() 的时候, flysystem 还没有初始化完成.
 *    要想让他初始化完成再执行,就得提前调用它的方法,然后进行附加动作.
 * 
 */
class Initable extends Component
{
    
    /**
     * @var array 执行的处理器.
     */
    public $handlers = [];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initHandlers();
    }

    
    /**
     * 开始初始化执行处理器.
     * 
     */
    public function initHandlers()
    {
        foreach($this->handlers as $name => $handler){
            $component = Yii::$app->$name;
            call_user_func($handler, $component);
        }
    }

}