<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
?>
<div class="container">
    <div class="col-xs-12">
    	<a class="pull-left" title="" href="#">登录</a>
    	<a class="pull-right" title="" href="#">手机号码</a>
    </div>
</div>
<?php 
	NavBar::begin([
		'options' => [
			'class' => 'navbar navbar-inverse'
		],
		'brandLabel' => Yii::$app->name,
	    'brandUrl'   => '#',
	    'renderInnerContainer' => true,
	    'innerContainerOptions' => [
	        'class' => ['container-fluid'],
	    ],
	]);
    

	echo Nav::widget([
	   	    'options' => [
	            'class' => ['nav', 'navbar-nav'],
	   	    ],
	        'items' => [
	            [
	                'label' => 'first',
	                'url' => '#',
	            ],
	        ],
	   ]);

    NavBar::end();
?>

