<?php

namespace backend\form;

use Yii;
use yii\db\ActiveRecord;
use common\models\Enroll;

class EnrollSearch extends Enroll
{
    use Search;

    public function rules()
    {
        return [
            [['id', 'name', 'email', 'phone', 'dob', 'sex', 'status', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return ActiveRecord::scenarios();
    }

    protected function _search($success)
    {
        $query = Enroll::find();
        if($success) {
            $query->andFilterWhere([
               'and',
               ['id' => $this->id, 'sex' => $this->sex],
               ['like', 'name', $this->name],
               ['like', 'email', $this->email],
               ['like', 'phone', $this->phone],
               ['like', 'dob', $this->dob],
               ['like', 'sex', $this->sex],
               ['status' => $this->status],
            ]);
        }
        return $query;
    }
}