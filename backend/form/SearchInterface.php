<?php

namespace backend\form;

use Yii;

interface SearchInterface
{
	
	/**
	 * 搜索模型
	 * @param  array $params  用户的请求参数
	 * @return yii\data\ActiveProvider
	 */
	public function search($params);

}