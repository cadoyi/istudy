<?php

namespace backend\form;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use common\models\Customer;
use common\models\CustomerEmail;

class CustomerSearch extends Customer
{
	  use Search;

    public function rules()
    {
        return [
           [['id', 'email', 'phone', 'nickname', 'is_active', 'created_at'],'safe'],
        ];
    }


	protected function _search($success)
	{
  		$query = static::find();

      if($success) {
           $query->andFilterWhere(['id' => $this->id])
             ->andFilterWhere(['like', 'phone', $this->phone])
             ->andFilterWhere(['like', 'nickname', $this->nickname])
             ->andFilterWhere(['like', 'email', $this->email])
             ->andFilterWhere(['is_active' => $this->is_active]);
      }
  		return $query;
	}
}