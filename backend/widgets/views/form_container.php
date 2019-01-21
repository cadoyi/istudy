<?php
use yii\helpers\Html;
$context = $this->context;
?>
<div id="form_container" class="row form-container">
	<div class="col-xs-3 tab-container">
		<ul id="tabs" class="list-group form-tab">
			<li class="list-group-item tab-title">
				<?= Html::encode(Yii::t('admin', $context->tabTitle)) ?>
			</li>
			<?php foreach($context->tabs() as $tab): ?>
				<?php 
				    $itemClass = 'list-group-item';
				    $title = Html::encode($tab['title']);
				?>
				<?php if(isset($tab['active']) && $tab['active']): ?>
                    <?php $itemClass .= ' active'; ?>
				<?php endif; ?>
				<li class="<?= $itemClass ?>">
				    <a title="<?= $title ?>" href="#<?= $tab['target'] ?>">
				    	<?php if(isset($tab['icon'])): ?>
				    		<span class="glyphicon glyphicon-<?= $tab['icon'] ?>">
				    		</span> 
				    	<?php endif; ?>
				    	<?= $title ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="col-xs-9 form-content-container">
		
		<div class="col-xs-12 text-right button-bar">
             <?= $context->renderButtons(); ?>
		</div>
		<div class="col-xs-12 form-content">
			    <?= $content; ?>
		</div>
	</div>
</div>