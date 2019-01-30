<?php

namespace backend\form;

use Yii;
use common\models\Post;
use common\models\PostContent;

class PostSearch extends Post
{
    use Search;

	public function rules()
	{
		return [
           [['id','url_path','title', 'description', 'status', 'category_id'],'safe'],
        ];
	}

    public function _search($success)
    {
        $post = Post::find()->filterOnlyCategory(false);
        if($success) {
            $post->andFilterWhere(['id' => $this->id])
                 ->andFilterWhere(['like', 'url_path',$this->url_path])
                 ->andFilterWhere(['like', 'title', $this->title])
                 ->andFilterWhere(['like', 'description', $this->description])
                 ->andFilterWhere(['status' => $this->status])
                 ->andFilterWhere(['category_id' => $this->category_id]);
        }
        return $post;
    }
}