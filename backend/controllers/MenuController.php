<?php

namespace backend\controllers;

use Yii;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use backend\form\MenuSearch;
use backend\form\MenuItemForm;
use common\models\Menu;
use common\models\MenuItem;
use core\exception\ValidateException;

class MenuController extends Controller 
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   'delete' => ['POST'],
                   'save-items' => ['POST'],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::className(),
                'only' => [ 'save-items' ],
            ],
        ]);
    }

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
        $this->_title('Create menu');
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
        $this->_title('Update menu');
        $menu =$this->findMenu($id);
        $menu->scenario = Menu::SCENARIO_UPDATE;
        if($post = $this->post) {
            if($menu->load($post) && $menu->validate()) {
                $menu->save(false);
                return $this->redirect(['index']);
            }
        }
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
		return $this->findModel($id, Menu::className());
	}

    public function actionSaveItems($menu_id)
    {
        $data = ['error' => 0, 'message' => ''];
        try {
            $menu = $this->findMenu($menu_id);
            $items = MenuItemForm::saveItems(Yii::$app->request->post('items', []), $menu);
        } catch(ValidateException $e) {
            $data['error'] = 1;
            $data['message'] = $e->getMessage();
        } catch(\Exception $e) {
            $data['error'] = 1;
            $data['message'] = 'Internal Server Error';
        } catch(\Throwable $e) {
            $data['error'] = 1;
            $data['message'] = 'Internal Server Error';
        }
        return $this->asJson($data);
    }
}