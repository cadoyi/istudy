<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Tag;

class TagController extends Controller 
{

    public function actionIndex($id)
    {
        $tag = $this->findTag($id);
        $query = $tag->getPosts()
           ->selectWithoutContent()
           ->with('tags');
           
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', [
           'tag' => $tag,
           'dataProvider' => $dataProvider,
        ]);
    }

    public function findTag($id)
    {
        $tag = Tag::findOne($id);
        return $tag ? : $this->notFound();
    }
}