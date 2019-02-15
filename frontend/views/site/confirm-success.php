<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<h1>感谢您注册学吧网!</h1>
<p>您已经成功激活您的账户! 5秒内为您跳转到登录界面</p>
<script>
    setTimeout(function(){
        window.location.href = '<?= Url::to(['site/login'], true) ?>';
    },5000);
</script>