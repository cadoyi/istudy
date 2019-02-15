<?php

namespace backend\controllers;

use Yii;
use common\models\Tag;
use backend\form\TagSearch;

class TagController extends Controller
{

    public function actionIndex()
    {
        $filterModel = new TagSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->get());
        return $this->render('index', [
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $this->_title('Create tag');
        $tag = new Tag(['scenario' => Tag::SCENARIO_CREATE]);
        if($post = $this->post) {
        	if($tag->load($post) && $tag->validate()) {
        		$tag->save(false);
        		return $this->redirect(['index']);
        	}
        }
        return $this->render('edit', ['tag' => $tag]);
    }

    public function actionUpdate($id)
    {
        $this->_title('Update tag');
        $tag = $this->findTag($id);
        $tag->scenario = Tag::SCENARIO_UPDATE;

        if($post = $this->post) {
        	if($tag->load($post) && $tag->validate()) {
        		$tag->save(false);
        		return $this->redirect(['index']);
        	}
        }
        return $this->render('edit', ['tag' => $tag]);
    }

    public function actionView($id)
    {
        $tag = $this->findTag($id);
        return $this->asJson($tag);
    }

    public function actionDelete($id)
    {
        $tag = $this->findTag();
        $tag->delete();
        return $this->redirect(['index']);
    }

    public function findTag($id)
    {
        return parent::findModel($id, Tag::className());
    }


}