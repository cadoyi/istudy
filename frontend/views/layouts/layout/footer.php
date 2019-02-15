<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use core\helpers\App;
use common\models\Menu;
use yii\widgets\Menu as MenuWidget;

$footer = Menu::findByTitle('footer');
?>
<footer id="footer" class="footer">
    <div class="container">
        <div class="friendlinks col-xs-12 hidden-xs">
            <?php foreach($this->getAppParam('friendlinks', []) as $link): ?>
                <a title="<?= Html::encode($link['title']) ?>"
                   href="<?= Html::encode($link['url'])?>"
                >
                    <?= Html::encode($link['title']) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="site-links col-xs-12 hidden-xs disflex">
            <?php if($footer instanceof Menu): ?>
                <?= MenuWidget::widget([
                    'options' => [
                        'id' => 'footermenus',
                        'class' => 'disflex flex1',
                    ],
                    'items' => $footer->orderedItems,
                ])?>
                <div class="qrcode" style="max-width:120px;">
                    <img class="img-responsive" alt="扫二维码加我为好友" src="<?= App::getImageUrl('qrcode.jpg')?>" />
                </div>
            <?php else: ?>
                <?= Html::encode(Yii::t('all', 'Please create a menu named "footer" as content.')) ?>
            <?php endif; ?>
        </div>
        <div class="copyright col-xs-12">
            <div class="text-center">
                <span>&copy; <?= Html::encode($this->getAppParam('website.copyright')) ?></span>
                <span> 
                    <?= Html::encode($this->getAppParam('website.icp')) ?>      
                </span>
            </div>
        </div>
    </div>
</footer>