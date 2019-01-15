<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php
    NavBar::begin([
        'options' => [
            'id' => 'headbar',
            'class' => 'navbar navbar-inverse'
        ],
        'renderInnerContainer' => true,
        'innerContainerOptions' => [
            'class' => ['container-fluid'],
        ],
        'brandLabel' => Html::encode(Yii::t('admin', Yii::$app->name)), 
        'brandUrl' => Yii::$app->homeUrl,
    ]);
?>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               <?= Html::encode(Yii::$app->user->identity->nickname) ?>
               <span class="caret"></span>
           </a>
           <ul class="color-inverse dropdown-menu">
             <li>
                <?= Html::beginForm(['site/logout'], 'post', [
                    'class' => ['form'],
                ]) ?>
                <?= Html::submitButton('logout', [
                    'class' => 'btn btn-link btn-block'
                ]) ?>
                <?= Html::endForm() ?>
              <li>
            </ul>
        </li>
    </ul>

<?php
   NavBar::end();
?>
