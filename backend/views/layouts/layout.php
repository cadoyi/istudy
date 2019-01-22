<?php
use yii\helpers\Html;
use yii\helpers\Url;
$name = Html::encode(Yii::t('admin', Yii::$app->name));
$nickname = Html::encode(Yii::$app->user->identity->nickname);
?>
<?php $this->beginContent('@backend/views/layouts/base.php') ?>
      <div class="page">
          <div id="page_menus" class="page-menus">
              <div class="color-inverse main-menubar">
                  <div class="brand">
                      <a class="website_name" title="<?= $name ?>" href="#">
                          <span class="glyphicon glyphicon-fire"></span>
                          <span class="text-label">
                          <?= $name; ?>
                          </span>
                      </a>
                      <a class="website_user">
                          <span class="text-label">
                              <?= $nickname; ?>
                          </span>
                      </a>
                  </div>
                  <?= $this->render('layout/menu') ?>
              </div>
          </div>

          <div id="page_content" class="page-content full">
              <div class="page-head">
                  <?= $this->render('layout/header') ?>
              </div>     
              <div class="container-fluid page-content-container">
                  <?= $content; ?>
              </div>
          </div>
      </div>
<?php $this->endContent(); ?>
