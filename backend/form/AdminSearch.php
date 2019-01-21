<?php

namespace backend\form;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

class AdminSearch extends User
{


    public function rules()
    {
        return [
            [['id', 'username', 'nickname', 'email', 'is_active', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = User::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 1],
        ]);

        if($this->load($params) && $this->validate()) {
            $query 
               -> andFilterWhere(['id' => $this->id, 'is_active' => $this->is_active])
               -> andFilterWhere(['like', 'username', $this->username ])
               -> andFilterWhere(['like', 'nickname', $this->nickname ])
               -> andFilterWhere(['like', 'email', $this->email]);
        }
        return $provider;
    }

}