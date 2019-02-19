<?php

namespace common\query;

/**
 * This is the ActiveQuery class for [[\common\models\Enroll]].
 *
 * @see \common\models\Enroll
 */
class EnrollQuery extends \common\query\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Enroll[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Enroll|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
