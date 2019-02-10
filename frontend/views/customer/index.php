<?php
use yii\helpers\Html;
use yii\helpers\Url;

$customer = Yii::$app->user->identity;
$profile = $customer->profile;
$this->registerCssFile('@web/css/login.css', ['depends' => ['common']]);
?>
<?php
/**
 *
 * 
 */
?>
<div class="container" style="min-width:768px;">
	<div class="row">
		<a title="" href="<?= Yii::$app->homeUrl ?>">
		    首页
		</a>
	</div>
	<div class="row" style="border-left:1px solid #ddd; border-right:1px solid #ddd;">
		<div class="col-xs-12 customer-info">
			<div class="col-xs-3 avators">
				<div class="avator">
					<?php if($profile->avatorUrl): ?>
					    <img alt="avator" src="<?= $profile->avatorUrl ?>" />
				    <?php endif; ?>
				</div>
				<div class="nickname"><?= Html::encode($customer->nickname) ?></div>
			</div>
			<div class="col-xs-9">
				<div class="col-xs-12">
					<ul class="nav pull-right">
						<li>
							<a title="" href="#">
							    <?= Html::encode($customer->email) ?>
						    </a>
						</li>
						<li>
						 <a title="" href="#">
						      <span class="glyphicon glyphicon-bell"></span>
						      消息
						</a>
					    </li>
						<li>
						    <a class="logout" 
						       title="<?= Yii::t('all', 'Logout')?>" 
						       href="<?= Url::to(['site/logout'])?>"
						       data-method="post"
						       data-confirm="<?= Yii::t('all', 'Are your sure')?>"
						    >
						       <span class="glyphicon glyphicon-log-out"></span>
						        注销
						    </a>
					    </li>
					</ul>
				</div>
				<div class="col-xs-12 text-center bio">
			        <?= Html::encode($profile->bio) ?>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<ul class="list-group">
				<li class="list-group-item row">
                       <a title="" href="#">
                       	    我关注的文章 
                       	    <span class="glyphicon glyphicon-arrow-right">
                       	    </span>
                       </a>
				</li>
				<li class="list-group-item row">
                    <a title="" href="#">
                    	我的评论
                   	    <span class="glyphicon glyphicon-arrow-right">
                   	    </span>
                    </a>
				</li>
			</ul>
		</div>
	</div>
</div>