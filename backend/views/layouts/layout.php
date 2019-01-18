<?php

?>
<?php $this->beginContent('@backend/views/layouts/base.php') ?>
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
<?php $this->endContent(); ?>
