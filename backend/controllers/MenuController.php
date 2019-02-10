<?php

namespace backend\controllers;

use Yii;
use backend\form\MenuSearch;
use common\models\Menu;
use common\models\MenuItem;

class MenuController extends Controller 
{

	public function actionIndex()
	{
		$filterModel = new MenuSearch();
		$dataProvider = $filterModel->search(Yii::$app->request->get());
		return $this->render('index', [
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
		]);
	}

	public function actionCreate()
	{
        $menu = new Menu(['scenario' => Menu::SCENARIO_CREATE]);
        if($post = $this->post) {
        	if($menu->load($post) && $menu->validate()) {
        		$menu->save(false);
        		return $this->redirect(['update', 'id' => $menu->id]);
        	}
        }
        return $this->render('edit', [
            'menu' => $menu,
        ]);
	}

	public function actionUpdate($id)
	{
        $menu =$this->findMenu($id);
        $menu->scenario = Menu::SCENARIO_UPDATE;
        return $this->render('edit', [
            'menu' => $menu,
        ]);        
	}

	public function actionDelete($id)
	{
        $this->findMenu($id)->delete();
        return $this->redirect(['index']);
	}

	public function findMenu($id)
	{
		return $this->findModel($id, Menu::className(), function($query) {
			$query->with('items');
		});
	}
}