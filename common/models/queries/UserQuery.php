<?php

namespace common\models\queries;

use Yii;
use core\db\ActiveQuery;
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
        return $this->andWhere(['is_active' => $bool ? 1 : 0]);
    }


    
}