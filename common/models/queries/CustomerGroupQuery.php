<?php

namespace common\models\queries;

use Yii;
use core\db\ActiveQuery;

class CustomerGroupQuery extends ActiveQuery
{

	public function filterDefault($bool = true)
	{
        return $this->andWhere(['is_default' => $bool ? 1 : 0]);
	}

}