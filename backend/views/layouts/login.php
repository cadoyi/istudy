<?php
use backend\assets\LoginAsset;
LoginAsset::register($this);
?>
<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
      <div class="container">
          <?= $content; ?>
      </div>
<?php $this->endContent() ?>
