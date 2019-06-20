<?php
use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
$bodyClass = str_replace('/', '-', Yii::$app->controller->route);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" style="font-size:100%;">
   <head>
       <meta charset="<?= Yii::$app->charset; ?>" />
       <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
       <?= Html::csrfMetaTags(); ?>
       <?= $this->head(); ?>
       <title><?= Html::encode($this->title) ?></title>
   </head>
   <body class="<?= $this->getBlock()->bodyClass ?>">
      <?php $this->beginBody(); ?>
       <?= $content; ?>
      <?php $this->endBody(); ?>
   </body>
</html>
<?php $this->endPage(); ?>