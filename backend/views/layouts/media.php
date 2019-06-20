<?php 
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $content string
 *
 * 
 */
$this->getBlock()->addBodyClass('medias');
?>
<?php $this->beginContent('@backend/views/layouts/base.php') ?>
    <?= $content ?>
<?php $this->endContent() ?>