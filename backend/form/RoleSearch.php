<?php

namespace backend\form;

use Yii;
use backend\models\AuthItem;

class RoleSearch extends AuthItem
{
    use Search;

    public function rules()
    {
        return [
           [['name', 'description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    protected function _search($success)
    {
        $query = AuthItem::findRole();
        if($success) {
            $query->andFilterWhere([
               'and',
               ['like', 'name', $this->name],
               ['like', 'description', $this->description],
            ]);
        }
        return $query;
    }
} 