<?php

namespace core\web;

use Yii;
use yii\web\NotFoundHttpException;

class Controller extends \yii\web\Controller
{
 
    protected function _title($title)
    {
    	$title = Yii::t('all', $title);
    	$this->getView()->title = $title;
    }

    protected function _keywords($keywords)
    {
    	$keywords = Yii::t('all', $keywords);
        $this->getView()->registerMetaKeywords($keywords);
    }

    protected function _description($description)
    {
    	$des = Yii::t('all', $description);
        $this->getView()->registerMetaDescription($des);
    }


    public function notFound()
    {
        throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
    }

}