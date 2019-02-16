<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Url::to(['site/reset-password', 
    'token' => $customer->getPasswordResetToken(), 
    'id' =>$customer->id], true);
?>
您好 <?= $customer->nickname ?>,

点击或者复制下面的链接来重置您的密码:

<?= $link ?>
