<?php

namespace backend\form;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Customer;
use common\models\CustomerEmail;

class CustomerSearch extends Customer
{
	use Search;

    protected $_email;

    public function rules()
    {
        return [
           [['id', 'email', 'phone', 'nickname', 'is_active', 'created_at'],'safe'],
        ];
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'email' => Yii::t('all', 'Email address'),
        ]);
    }

    /**
     * 
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function search($params)
    {
        return new ActiveDataProvider([
            'query' => $this->_getSearchQuery($params),
            'sort' => [
               'attributes' => [
                   'id',
                   'phone',
                   'nickname',
                   'is_active',
                   'created_at',
                   'email' => [
                       'asc'  => ['ce.email' => SORT_ASC],
                       'desc' => ['ce.email' => SORT_DESC],
                       'default' => SORT_ASC,
                   ],
               ],
            ],
        ]);
    }


	protected function _search($success)
	{
		$query = static::find()
             -> select(['c.*', 'ce.email'])
             -> alias('c') 
             -> leftJoin(['ce' => CustomerEmail::tableName()], 'c.id = ce.customer_id and ce.is_primary = 1');
		return $query;
	}
}