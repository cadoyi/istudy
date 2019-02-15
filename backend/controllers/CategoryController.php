<?php

namespace backend\controllers;

use Yii;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Category;
use common\models\Post;
use common\models\PostContent;
use backend\form\CategorySearch;
use core\exception\ValidateException;

class CategoryController extends Controller
{


    /**
     * 分类列表
     */
	public function actionIndex()
	{
        $filterModel = new CategorySearch();
        $dataProvider = $filterModel->search(Yii::$app->request->get());
        return $this->render('index', compact('filterModel', 'dataProvider'));
	}


    /**
     * {@inheritdoc}
     */
	public function actionCreate()
	{
        $this->_title('Manage category');
        $category = new Category(['scenario' => Category::SCENARIO_CREATE]);
        if($post = $this->post) {
            if($category->load($post) && $category->validate()) {
                Category::getDb()->transaction(function() use ($category) {
                    $category->save(false);
                    $category->changePathAndLevel();
                    $category->save(false);
                });
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', [
           'category'    => $category,
        ]);
	}

	public function actionUpdate($id)
	{
        $this->_title('Update category');
        $category = $this->findCategory($id);
        $category->scenario = Category::SCENARIO_UPDATE;

        if($post = $this->post) {
            if($category->load($post) && $category->validate()) {
                Category::getDb()->transaction(function() use ($category){
                    $category->save(false);
                    $category->changePathAndLevel();
                    $category->save(false);
                });
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit', [
           'category' => $category,
        ]);
	}

    public function actionView($id)
    {
        $category = $this->findCategory($id);
        return $this->asJson($category);
    }

	public function actionDelete($id)
	{
        $category = $this->findCategory($id);
        if($category->canDelete()) {
            $category->delete();
        }
        return $this->redirect(['index']);
	}

    public function findCategory($id)
    {
        return $this->findModel($id, Category::className());
    }


}