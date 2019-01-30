<?php

namespace core\web;

use Yii;

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
    	$this->getView()->registerMetaTag([
           'name' => 'keywords',
           'content' => $keywords,
    	], 'keywords');
    }

    protected function _description($description)
    {
    	$des = Yii::t('all', $description);
    	$this->getView()->registerMetaTag([
            'name' => 'description',
            'content' => $des,
    	], 'description');
    }

}