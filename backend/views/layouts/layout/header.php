<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div id="header" class="container_fluid">
    <div class="row col-nomag">
       <div class="col-xs-12 col-nopaid" style="min-height: 50px;line-height: 50px; ">
           <div class="col-xs-2 col-nopaid">
               <a id="switchmenu" class="pull-left switch-menu" title="switch menu" href="#">
                  <span class="glyphicon glyphicon-menu-hamburger"></span>
               </a>
           </div>
           <div class="col-xs-10 text-right">
              <div class="dropdown pull-right">
                 <a href="#" class="btn dropdown-toggle" data-toggle="dropdown">
                     <span class="glyphicon glyphicon-user"></span>
                     <?= Html::encode(Yii::$app->user->identity->nickname) ?>
                     <span class="caret"></span>
                 </a>
                 <ul class="dropdown-menu">
                     <li>
                        <a class="btn btn-link" 
                           href="<?= Url::to(['site/logout']) ?>" 
                           data-method="post"
                           data-confirm="<?= Html::encode(Yii::t('admin', 'Are you sure you want to logout?'))?>"
                          >
                            <?= Yii::t('admin', 'Logout') ?>     
                        </a>
                    <li>
                </ul>
              </div>

              <div class="pull-right message-box">
                <a class="btn" href="#" class="dropdown-toggle" data-toggle="dropdown">
                   <span class="glyphicon glyphicon-envelope"></span>
                   消息列表
                   <span class="badge">3</span>
                </a>
                <ul class="dropdown-menu messages">
                    <li>
                       <a title="" href="#">
                           <span class="subject">
                               <span class="sender">John Smith</span>
                               <span class="sendtime">3 mins ago</span>
                           </span>
                           <span class="message">
                               你的邮箱中有三封未读邮件,请您及时查收
                           </span>
                       </a>
                    </li>
                    <li>
                       <a title="" href="#">
                           <span class="subject">
                               <span class="sender">John Smith</span>
                               <span class="sendtime">3 mins ago</span>
                           </span>
                           <span class="message">
                               你的邮箱中有三封未读邮件,请您及时查收
                           </span>
                       </a>
                    </li>
                    <li>
                       <a title="" href="#">
                           <span class="subject">
                               <span class="sender">John Smith</span>
                               <span class="sendtime">3 mins ago</span>
                           </span>
                           <span class="message">
                               你的邮箱中有三封未读邮件,请您及时查收
                           </span>
                       </a>
                    </li>
                    <li class="last">
                        <a title="" href="#">
                           查看所有消息
                           <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </li>
                </ul>
              </div>  

           </div>
       </div>
    </div>
</div>
