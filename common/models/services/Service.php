<?php

namespace common\models\services;

use Yii;
use yii\base\Component;
use common\components\StaticInstanceTrait;


/**
 * 所有 service 层的基类.
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
class Service extends Component
{
    use StaticInstanceTrait;    
}