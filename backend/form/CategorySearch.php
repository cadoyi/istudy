<?php

namespace backend\form;

use Yii;
use common\models\Category;

class CategorySearch extends Category
{
    use Search;

    public function rules()
    {
    	return [
           [['title', 'url_path', 'parent_id', 'path', 'level', 'created_at', 'updated_at'], 'safe'],
    	];
    }

    protected function _search($success)
    {
    	$query = static::find();
        if($success) {
        	$query->andFilterWhere(['like', 'title', $this->title])
        	  ->andFilterWhere(['like', 'url_path', $this->url_path])
              ->andFilterWhere(['parent_id' => $this->parent_id]);
        }
    	return $query;
    }
} 