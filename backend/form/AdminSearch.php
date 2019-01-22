<?php

namespace backend\form;

use Yii;
use backend\models\User;

class AdminSearch extends User
{
    use Search;

    public function rules()
    {
        return [
            [['id', 'username', 'nickname', 'email', 'is_active', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    protected function _search($success)
    {
        $query = static::find();
        if($success) {
            $query 
            -> andFilterWhere(['id' => $this->id, 'is_active' => $this->is_active])
            -> andFilterWhere(['like', 'username', $this->username ])
            -> andFilterWhere(['like', 'nickname', $this->nickname ])
            -> andFilterWhere(['like', 'email', $this->email]);            
        }
        return $query;
    }

}