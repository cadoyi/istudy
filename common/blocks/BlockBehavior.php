<?php

namespace common\blocks;

use Yii;

use yii\base\Behavior;

/**
 *
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
class BlockBehavior extends Behavior
{

    use BlockTrait;
    
   
    /**
     * @inheritdoc
     *
     * 附加行为的时候注册 blocks
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $this->registerBlocks();
    }


} 