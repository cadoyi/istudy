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
           [['title', 'url_path'], 'safe'],
    	];
    }

    protected function _search($success)
    {
    	$query = static::find();
        if($success) {
        	$query->andFilterWhere(['like', 'title', $this->title])
        	  ->andFilterWhere(['like', 'url_path', $this->url_path]);
        }
    	return $query;
    }
} 