<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */


$link = Url::to(['site/confirm-email', 'token' => $user->auth_key, 'sequeue' => $user->id, 'secret' => $secret], true);
?>
<div class="password-reset">
    <p>你好 <?= Html::encode($user->nickname) ?>,欢迎您注册学吧网</p>

    <p>请点击或者复制下面的链接来验证您的邮箱:</p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
