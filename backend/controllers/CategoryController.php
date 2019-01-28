<?php

namespace backend\controllers;

use Yii;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Category;
use backend\form\CategorySearch;

class CategoryController extends Controller
{

    /**
     * {@inheritdoc}
     */
	public function behaviors()
	{
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'ajax' => [
            	'class' => AjaxFilter::className(),
                'only'  => ['view'],
            ],
        ]);
	}

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
        $category = new Category(['scenario' => Category::SCENARIO_CREATE]);
        $request = Yii::$app->request;
        if($request->isPost) {
            $post = $request->post();
            if($category->load($post) && $category->validate()) {
                Category::getDb()->transaction(function() use ($category) {
                    $category->save(false);
                    $category->changePathAndLevel();
                    $category->save(false);
                });
                $this->redirect(['index']);
            }
        }
        return $this->render('edit', [
           'category' => $category,
        ]);
	}

	public function actionUpdate($id)
	{
        $category = $this->findCategory($id);
        $category->scenario = Category::SCENARIO_UPDATE;
        $request = Yii::$app->request;

        if($request->isPost) {
            $post = $request->post();
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
           'category' => $category,
        ]);
	}

    public function actionView($id)
    {
        $category = $this->findModel($id, Category::className());
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