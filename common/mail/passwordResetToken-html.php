<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Url::to(['site/reset-password', 
     'token' => $customer->getPasswordResetToken(),
     'id' => $customer->id,
], true);
?>
<div class="password-reset">
    <p>您好 <?= Html::encode($customer->nickname) ?>,</p>

    <p>点击或者复制下面的链接重置您的密码:</p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
