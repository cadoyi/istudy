<?php
use yii\helpers\Html;
use yii\helpers\Url;
use core\helpers\App;
use frontend\assets\HomeAsset;

HomeAsset::register($this);

/* @var $this yii\web\View */

$this->title = App::getParam('website.meta_title', '学吧网');
$this->registerMetaKeywords();
$this->registerMetaDescription();
$this->registerJs('$("#banner").slick({
    dots : true,
    speed : 300,
    autoplay: true,
    autoplaySpeed : 2000,
    arrows : true
});');

$image = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MTgwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTY5MGY3ODIyNzggdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNjkwZjc4MjI3OCI+PHJlY3Qgd2lkdGg9IjE3MSIgaGVpZ2h0PSIxODAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI1OS41NTQ2ODc1IiB5PSI5NC41Ij4xNzF4MTgwPC90ZXh0PjwvZz48L2c+PC9zdmc+';
?>
<style>
    .banner img { width:100%; }
</style>
<div class="home-page">
    <div id="banner" class="banner slider">
        <a title="" class="thumbnal" href="#">
            <img class="img-responsive" alt="01" src="<?= App::getImageUrl('home/1.jpg')?>"/>
        </a>
        <a title="" class="thumbnal" href="#">
            <img class="img-responsive" alt="02" src="<?= App::getImageUrl('home/2.jpg')?>"/>
        </a>
        <a title="" class="thumbnal" href="#">
            <img class="img-responsive" alt="03" src="<?= App::getImageUrl('home/3.jpg')?>"/>
        </a>
        <a title="" class="thumbnal" href="#">
            <img class="img-responsive" alt="04" src="<?= App::getImageUrl('home/4.jpg')?>"/>
        </a>        
    </div>
    <div class="home-content">
        <div class="panel panel-default">
            <div class="panel-heading">相关板块</div>
            <div class="panel-body">
                <div class="col-xs-12">
                    <div class="col-xs-4">
                        <a title="" class="thumbnal" href="#">
                            <img alt="" src="<?= $image?>">
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <a title="" class="thumbnal" href="#">
                            <img alt="" src="<?= $image?>">
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <a title="" class="thumbnal" href="#">
                            <img alt="" src="<?= $image?>">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="panel panel-default">
            <div class="panel-heading">海外扩展</div>
            <div class="panel-body">
               去吧,这个世界需要你去开采.
            </div>
        </div>
    </div>
        <div class="panel panel-default">
            <div class="panel-heading">关于我们</div>
            <div class="panel-body">
                我们是一支优秀的团队
            </div>
        </div>
    </div>
</div>
