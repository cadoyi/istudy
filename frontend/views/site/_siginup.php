<?php
use yii\helpers\Html;
use yii\helpers\Url;
$isLogin = Yii::$app->controller->route == 'site/login';
?>

<div class="site-sign-container">
    <div class="site-sign-row">
        <ul class="nav nav-tabs disflex">
            <li class="flex1 <?php if($isLogin): ?>active<?php endif; ?>">
                <a href="<?= Url::to(['site/login'])?>">
                    <?= Yii::t('app', 'Student login')?> 
                </a>
            </li>
            <li class="flex1 <?php if(!$isLogin): ?>active<?php endif; ?>">
                <a href="<?= Url::to(['site/signup']) ?>">
                    <?= Yii::t('app', 'Student register')?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
