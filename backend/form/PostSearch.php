<?php

namespace backend\form;

use Yii;
use yii\db\ActiveRecord;
use common\models\Post;
use common\models\PostContent;

class PostSearch extends Post
{
    use Search;

	public function rules()
	{
		return [
           [['id','url_path','title', 'description', 'is_active', 'category_id', 'created_at'],'safe'],
        ];
	}

    public function scenarios()
    {
        return ActiveRecord::scenarios();
    }

    public function _search($success)
    {
        $post = Post::find();
        if($success) {
            $post->andFilterWhere(['and', 
                ['id' => $this->id],
                ['like', 'url_path', $this->url_path],
                ['like', 'title', $this->title],
                ['like', 'description', $this->description],
                ['is_active' => $this->is_active],
                ['category_id' => $this->category_id],
            ]);
        }
        return $post;
    }
}