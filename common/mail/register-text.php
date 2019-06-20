<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
use yii\helpers\Url;

$link = Url::to(['site/confirm-email', 'token' => $user->auth_key, 'sequeue' => $user->id, 'secret' => $secret], true);
?>
你好 <?= $user->nickname ?>,欢迎您注册学吧网

请点击下面的链接来验证你的邮箱:

<?= $link ?>