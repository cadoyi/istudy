<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php
/**
 * @var $this yii\web\View
 * 
 */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>" style="font-size:100%;">
    <head>
    	<meta charset="<?= Yii::$app->charset; ?>" />
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <?= $this->head() ?>
    	<title><?= Html::encode($this->title)?></title>
    </head>
    <body class="<?= $this->bodyClass ?>">
    	<?php $this->beginBody() ?>
    	<?= $content; ?>
    	<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage(); ?>