<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$return = Yii::$app->user->getReturnUrl(Yii::$app->user->loginUrl);

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        出现这个错误是因为您的请求无法被服务器处理.
    </p>
    <p>
        如果你认为这是服务器出现的错误,那么你可以联系管理员.
    </p>
    <p>
        <?= Html::a('返回', $return, ['class' => 'btn btn-default']) ?>
    </p>

</div>
