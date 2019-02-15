<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
$params = &Yii::$app->params;
$user = Yii::$app->user;
?>
<?php $this->beginContent('@frontend/views/layouts/base.php') ?>
    	<div id="page" class="page">
	    	<div class="header">
                <div class="container-fluid page-alert">
                    <?= Alert::widget() ?>
                </div>
	    	    <div class="container-fluid">
                    <?= $this->render('@frontend/views/layouts/layout/menu.php') ?>
	    	    </div>
	    	</div>

	    	<div class="page-content container">
	    	    <?= $content; ?>
	        </div>
        </div>
        <?= $this->render('@frontend/views/layouts/layout/footer.php') ?>
<?php $this->endContent() ?>