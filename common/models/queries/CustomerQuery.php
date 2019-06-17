<?php

namespace common\models\queries;

use Yii;
use core\db\ActiveQuery;

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
        return $this->andWhere(['is_active' => $bool ? 1 : 0]);
    }




}