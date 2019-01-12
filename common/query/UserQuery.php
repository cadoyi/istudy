<?php

namespace common\query;

use Yii;
use common\models\User;

class UserQuery extends ActiveQuery
{
 

    /**
     * 过滤 is_active 参数.
     * 
     * @param  boolean $bool 
     * @return $this
     */
    public function filterActive($bool = true)
    {
        return $this->_filterBoolean('is_active', $bool);
    }


    /**
     * 过滤 is_deleted 参数.
     * 
     * @param  boolean $bool 
     * @return $this
     */
    public function filterDeleted($bool = true)
    {
        return $this->_filterBoolean('is_deleted', $bool);
    }

    
}