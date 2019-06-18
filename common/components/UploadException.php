<?php

namespace common\components;

use Yii;
use yii\base\Exception;

/**
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
class UploadException extends Exception
{

    /**
     * @inheritdoc
     * 
     * @return string
     */
    public function getName()
    {
        return 'UploadException';
    }

}