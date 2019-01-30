<?php

namespace backend\form;

use Yii;
use common\models\CustomerGroup;

class CustomerGroupSearch extends CustomerGroup
{
	use Search;

	public function rules()
	{
		return [
            [['id', 'name', 'description', 'is_default', 'created_at', 'updated_at'], 'safe'],
		];
	}

	protected function _search($success)
	{
		$query = CustomerGroup::find();
        if($success) {
        	$query->andFilterWhere([
        		'id' => $this->id,
        		'is_default' => $this->is_default,
        	])->andFilterWhere(['like', 'name', $this->name])
        	->andFilterWhere(['like', 'description', $this->description]);
        }
        return $query;
	}
}