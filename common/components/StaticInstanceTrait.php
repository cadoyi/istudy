<?php

namespace common\components;

use Yii;

/**
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
trait StaticInstanceTrait
{

 
    /**
     * 实例化当前模型类.
     * 
     * @param  array  $config 配置
     * @return static 实例化后的对象.
     */
    public static function instance( $config = [] )
    {
        return new static($config);
    }

}