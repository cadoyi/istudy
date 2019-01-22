<?php

namespace backend\form;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

Trait Search
{

    protected function _getSearchQuery($params)
    {
        if($this->load($params)) {
            if($this->validate()) {
                return $this->_search(true);
            }
        }
        return $this->_search(false);
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
        ]);
	}


    /**
     * 准备查询数据.
     * @param  bool $success 是否加载并验证成功
     * @return yii\db\ActiveQuery
     */
	protected function _search($success)
    {

    }
}