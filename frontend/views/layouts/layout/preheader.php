<?php 
use yii\helpers\Html;
use yii\helpers\Url;

$user = Yii::$app->user;
$params = &Yii::$app->params;
?>
<div id="preheader" class="row preheader initiala">
    <div class="col-xs-12">
		<div class="col-xs-6">
            <?php if($user->isGuest): ?>
			<a title="<?= Yii::t('all', 'Customer login')?>" 
			   href="<?= Url::to(['site/login']) ?>">
				<?= Yii::t('all', 'Customer login') ?>
			</a>
            <?php else: ?>
                <a title="<?= Yii::t('all', 'Personal')?>" 
                   href="<?= Url::to(['customer/index']) ?>">
                    <?= Html::encode($user->identity->nickname); ?>
                </a>                                    
            <?php endif; ?>
		</div>
	    <div class="col-xs-6 text-right">
	    	<?= Html::encode($params['telephone']) ?>
	    </div>
    </div>
</div>