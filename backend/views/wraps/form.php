<?php
use yii\bootstrap\Modal;
?>
<div class="row">
    <?= $content ?>
    <?php Modal::begin() ?>
    <div id="view_content">
        
    </div>
    <?php Modal::end() ?>

</div>