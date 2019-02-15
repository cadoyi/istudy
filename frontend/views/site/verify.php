<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsVar('resentUrl', Url::to(['site/confirm-resent', 'email' => $email]));
$this->registerJs('$("#reconfirm").click(function(){
    $.post(resentUrl).done(function(){
        alert("发送成功");
    }).fail(function(){
        alert("发送失败");
    });
});');
?>
<div class="row" style="font-size:1rem;">
     <div class="col-xs-12">
        <h1><small style="color:#000;">感谢您注册学吧网!</small></h1>
    </div>
    <div class="col-xs-12">
        <p>为了保护您的权益,我们需要对您的真实身份进行确认.</p>
        <p>我们已经发送了一封邮件到 &lt;<?= Html::encode($email)?>&gt;,请您尽快登录邮箱来进行确认. </p>

        <div class="form-group">
            如果您没有收到邮件,请点此 
            <a id="reconfirm" class="btn btn-sm btn-danger"
                title="重新发送" 
                href="#"
            >重新发送</a> 
        </div>
    </div>
</div>