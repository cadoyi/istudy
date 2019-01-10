<?php

namespace common\models;

use Yii;

class PostContentQuery extends ActiveQuery
{

    /**
     * 筛选出最新的文章.
     * 
     * @return [type] [description]
     */
    public function filterPrimary()
    {
        return $this->orderBy(['id' => SORT_DESC])->limit(1);
    }


}