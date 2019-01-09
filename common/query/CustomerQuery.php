<?php

namespace common\query;

use Yii;

/**
 * 自定义的 customer 的 activeQuery
 *
 * 
 */
class CustomerQuery extends ActiveQuery
{


    /**
     * 过滤 is_active 状态.
     * 
     * @param  boolean $bool 
     * @return $this
     */
    public function filterActive($bool = true)
    {
        return $this->_filterBoolean('is_active', $bool);
    }




}