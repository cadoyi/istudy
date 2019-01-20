<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->registerTabJs();
?>
<div class="row">		
	<div class="col-xs-3">
		<ul id="<?= $this->tabId ?>" class="list-group form-tab">
			<li class="list-group-item tab-title">
				<?= Html::encode($this->tabTitle) ?>
			</li>
			<?php foreach($this->tabs as $id => $tab): ?>
                <li class="list-group-item<?php if($this->isActiveTab($id)): ?> active<?php endif; ?>">
                	<a title="<?= Html::encode($tab)?>" href="#<?= $id?>">
                		<?= Html::encode($tab) ?>
                	</a>
                </li>
			<?php endforeach; ?>			
		</ul>
	</div>
	<div class="col-xs-9">
		<?= $content; ?>
	</div>
</div>