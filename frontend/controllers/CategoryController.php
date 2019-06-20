<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Category;
use common\models\Post;

class CategoryController extends Controller 
{

    public function actionView($id)
    {
        /*
         * @todo
         */ 
        $this->notFound();
        $category = $this->findCategoryWithContent($id);
    }

    public function actionIndex($id)
    {
        $category = $this->findCategory($id);
        $query = $category->getPosts()
            -> selectWithoutContent()
            -> with('tags');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
               'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $provider,
            'category' => $category,
        ]);
    }

    public function findCategory($id)
    {
        $category = Category::find()
            -> where(['id' => $id, 'is_active' => 1])
            -> selectWithoutContent()
            -> one();

        if(!$category) {
            return $this->notFound();
        }
        return $category;
    }

    public function findCategoryWithContent($id)
    {
        $category = Category::findOne(['id' => $id, 'is_active' => 1]);
        if(!$category) {
            return $this->notFound();
        }
        return $category;
    }
}