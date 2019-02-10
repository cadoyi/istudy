<?php 
use yii\helpers\Html;
use yii\helpers\Url;
?>
<footer class="footer">
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
        <div class="site-links col-xs-12 hidden-xs">
            <div class="col-xs-3">
                <p class="level0">
                    <a href="#" title="student">Student</a>
                </p>
            <ul class="list-unstyled">
                <li><a href="#" title="">student catalog</a></li>
                <li><a href="#" title="">student catalog</a></li>
                <li><a href="#" title="">student catalog</a></li>
                <li><a href="#" title="">student catalog</a></li>
            </ul>
            </div>
            <div class="col-xs-3">
                <p class="level0">
                    <a href="#" title="student">Student</a>
                </p>
                <ul class="list-unstyled">
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                </ul>
            </div>
            <div class="col-xs-3">
                <p class="level0">
                    <a href="#" title="student">Student</a>
                </p>
                <ul class="list-unstyled">
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                </ul>
            </div>
            <div class="col-xs-3">
                <p class="level0">
                    <a href="#" title="student">Student</a>
                </p>
                <ul class="list-unstyled">
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                    <li><a href="#" title="">student catalog</a></li>
                </ul>
            </div>
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