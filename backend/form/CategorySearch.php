<?php

namespace backend\form;

use Yii;
use yii\db\ActiveRecord;
use common\models\Category;

class CategorySearch extends Category
{
    use Search;

    public function rules()
    {
    	return [
           [
                [
                    'id',
                    'title', 
                    'description', 
                    'url_path', 
                    'parent_id', 
                    'is_active',
                    'position',
                    'created_at',
                ], 
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
    	$query = static::find();
        if($success) {
        	$query->andFilterWhere(['and', 
                ['id' => $this->id],
                ['like', 'title', $this->title],
                ['like', 'description', $this->description],
                ['like', 'url_path', $this->url_path],
                ['is_active' => $this->is_active],
                ['position' => $this->position],
                ['parent_id' => $this->parent_id],
            ]);
        }
    	return $query;
    }
} 