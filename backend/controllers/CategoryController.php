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
        return $this->render('edit', [
           'category' => $category,
        ]);
	}

	public function actionUpdate($id)
	{

	}

    public function actionView()
    {

    }

	public function actionDelete($id)
	{

	}

	public function actionForm($id = 0)
	{
		throw new NotFoundHttpException('page not found');
		$data = [];
		if($id === 0) {
			$category = new Category(['scenario' => Category::SCENARIO_CREATE]);
            $data['success'] = true;
		} else {
			$category = $this->findModel($id, Category::className());
			$data['success'] = $category instanceof Category;
			if(!$data['success']) {
				$data['message'] = Yii::t('all', 'Category not exists');
                return $this->asJson($data);
			}
			$category->scenario = Category::SCENARIO_UPDATE;
		}
        $data['message'] = $this->renderPartial('_form', ['category' => $category]);
        return $this->asJson($data);
	}
}