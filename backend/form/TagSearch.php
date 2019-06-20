<?php

namespace backend\form;

use Yii;
use yii\db\ActiveRecord;
use common\models\Tag;

class TagSearch extends Tag
{
	use Search;

	public function rules()
	{
		return [
           [ 
           	   ['id', 'title', 'description','created_at', 'updated_at'],
               'safe',
           ],
		];
	}

	public function scenarios()
	{
        return ActiveRecord::scenarios();
	}


	protected function _search($success)
	{
		$query = Tag::find();
		if($success) {
			$query->andFilterWhere([ 'and',
				['id' => $this->id ],
                ['like', 'title', $this->title],
                ['like', 'description', $this->description],
			]);
		}
		return $query;
	}
}