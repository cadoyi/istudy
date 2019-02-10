<?php

namespace frontend\controllers;

use Yii;

class CustomerController extends Controller
{


    /**
     * 个人中心
     * 
     */
	public function actionIndex()
	{
		$this->layout = 'base';
		$this->_title('Personal');
        return $this->render('index');
	}
}