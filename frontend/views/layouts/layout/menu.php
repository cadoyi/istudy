<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Category;
use common\models\Menu;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use core\helpers\App;

$header = Menu::findByTitle('header');
?>
<div id="header_menus" class="row header-menus">
    
<?php 
    NavBar::begin([
        'id' => 'menubar',
        'options' => [
            'class' => ['menubar navbar-inverse navbar-fixed-top'],
        ],
        'innerContainerOptions' => [
            'class' => 'container',
        ],
        'brandImage' => App::getImageUrl('weblogo.jpg'),
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => [
            'style' => [
                'padding' => 0,
            ],
        ],
    ]);
?>
<?php if($header instanceof Menu): ?>
    <?= Nav::widget([
        'items' => $header->getOrderedItems(),
        'id' => 'menuitems',
        'options' => [
            'class' => 'nav navbar-nav',
        ],
    ]); ?>
<?php endif; ?>
    <?= Nav::widget([        
        'id' => 'enroll',
        'options' => [
            'class' => 'enroll-menu nav navbar-nav',
        ],
        'items' => [           
           [
               'label' => Yii::t('app', 'Enroll now'),
               'url' => Url::to(['site/login']),
           ],

        ],

    ]) ?>
    <?= Nav::widget([        
        'id' => 'loginnav',
        'options' => [
            'class' => 'loginbar nav navbar-nav',
        ],
        'items' => [           
            [
               'label' => Yii::t('app', 'Student login'),
               'url' => Url::to(['site/login']),
               'visible' => Yii::$app->user->isGuest,
            ],
            [
                'label' => Yii::$app->user->isGuest ? 
                      Yii::t('app', 'Personal') : 
                      Yii::$app->user->identity->nickname,

                'url' => '#',
                'visible' => !Yii::$app->user->isGuest,
                'items' => [
                    [
                        'label' => '<i class="fa fa-fw fa-cog"></i>' . Html::encode(Yii::t('app', 'Personal')),
                        'encode' => false,
                        'url' => Url::to(['customer/index']),
                    ],
                    [
                        'label' => '<i class="fa fa-fw fa-power-off" style="color:red;"></i> ' . Html::encode(Yii::t('app', 'Logout')),
                        'encode' => false,
                        'url' => Url::to(['site/logout']),
                        'linkOptions' => [
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('app', 'Are you sure you want to logout?'),
                        ],
                    ],
                ],
            ],

        ],

    ])
       
    ?>
<?php NavBar::end(); ?>
</div>