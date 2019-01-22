<?php

namespace backend\form;

use Yii;
use backend\models\Customer;

class CustomerSearch extends Customer
{
	use Search;

    public function rules()
    {
        return [
           [['id', 'nickname', 'phone', 'is_active', 'created_at', 'updated_at'],'safe'],
        ];
    }


	protected function _search($success)
	{
		$query = static::find();
		return $query;
	}
}