<?php

namespace fronend\controllers;

use Yii;
use common\models\Category;

class CategoryController extends Controller 
{

    public function actionView($id)
    {
        /*
         * @todo
         */ 
        $this->notFound();
        $category = $this->findCategory($id);
    }

    public function actionIndex($id)
    {
        $category = $this->findCategory($id);
        
    }

    public function findCategory($id)
    {
        $category = Category::findOne(['id' => $id, 'is_active' => 1]);
        if(!$category) {
            return $this->notFound();
        }
        return $category;
    }
}