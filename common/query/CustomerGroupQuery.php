<?php

namespace common\query;

use Yii;

class CustomerGroupQuery extends ActiveQuery
{

	public function filterDefault($bool = true)
	{
		return $this->_filterBoolean('is_default', $bool);
	}

}