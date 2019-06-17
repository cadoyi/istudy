<?php

namespace common\blocks;

use Yii;

/**
 * Block 接口
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
interface BlockInterface
{


    /**
     * 直接 echo 出来就可以.
     * 
     * @return string 
     */
    public function __toString();
    
}