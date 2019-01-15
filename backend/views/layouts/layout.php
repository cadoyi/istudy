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
   <body class="<?= $bodyClass ?>">
      <?php $this->beginBody(); ?>
      <div class="page">
          <div class="color-inverse page-head">
              <?= $this->render('layout/header') ?>
          </div>
          <div class="page-menus">
              <div class="color-inverse main-menubar">
                  <a id="menu_switcher" class="switcher" title="隐藏标签" href="#">
                      <span class="glyphicon glyphicon-arrow-left"></span>
                      <span class="switcher-text">隐藏</span>
                  </a>
                  <?= $this->render('layout/menu') ?>
              </div>
          </div>
          <div class="page-content">
              <div class="container-fluid">
                  <?= $content; ?>
              </div>
          </div>
      </div>

      <?php $this->endBody(); ?>
   </body>
</html>
<?php $this->endPage(); ?>