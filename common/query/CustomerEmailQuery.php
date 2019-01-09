<?php

namespace common\query;

use Yii;

class CustomerEmailQuery extends ActiveQuery
{
    
    /**
     * 过滤 is_public 属性,这个属性代表是否可以公开.
     * @param  boolean $bool 
     * @return $this
     */
    public function filterPublic($bool = true)
    {
        return $this->_filterBoolean('is_public', $bool);
    }



    /**
     * 过滤 is_primary 属性, 这个属性代表是否是主要的邮件地址.
     * @param  boolean $bool [description]
     * @return [type]        [description]
     */
    public function filterPrimary($bool = true)
    {
        return $this->_filterBoolean('is_primary', $bool);
    }


    /**
     * 过滤 can_login 属性, 这个属性代表此邮件地址是否可以用于登录.
     * @param  boolean $bool [description]
     * @return [type]        [description]
     */
    public function filterCanLogin($bool = true)
    {
        return $this->_filterBoolean('can_login', $bool);
    }
}