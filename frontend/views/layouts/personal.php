<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $content  视图内容
 *
 */
$customer = Yii::$app->user->identity;
$profile = $customer->profile;
?>
<?php $this->beginContent('@frontend/views/layouts/layout.php') ?>
<div class="col-xs-12">
    <div class="col-xs-12 col-sm-3" style="border:1px solid #ddd;text-align:center;">
        <div class="col-xs-12 avator-div">
            <h3>头像</h3>
            <img class="img-responsive" alt="头像" src="<?= $profile->getAvatorUrl() ?>" />
            <a title="<?= Html::encode($customer->nickname) ?>" href="<?= Url::to(['customer/index'])?>">
                <?= Html::encode($customer->nickname) ?>
            </a>
        </div>
        <div class="col-xs-12 links" style="margin-bottom:20px;">
            <h3>基本资料</h3>
            <?= Menu::widget([
                'options' => [
                    'class' => 'nav nav-stacked',
                ],
                'items' => [
                    [
                        'label' => '账号信息',
                        'url' => ['customer/index'],
                    ],
                    [
                        'label' => '密码信息',
                        'url'  => ['customer/password'],
                    ],
                    [
                        'label' => '联系方式',
                        'url'   => ['customer/contact'],
                    ],
                    [
                        'label' => '其他资料',
                        'url'   => ['customer/profile'],
                    ],
                    [
                        'label' => '我的收藏',
                        'url' => ['customer/favorite'],
                    ],
                    [
                        'label' => '我的通知',
                        'url' => ['customer/notification'],
                    ],
                ],
            ])?>
        </div>

    </div>
    <div class="col-xs-12 col-sm-9" style="padding:2rem;">
        <?= $content; ?>
    </div>
</div>

<?php $this->endContent() ?>