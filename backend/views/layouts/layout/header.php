<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
$user = Yii::$app->user->identity;
$messages = $user->getMessageList();
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
                     <?= Html::encode($user->nickname) ?>
                     <span class="caret"></span>
                 </a>
                 <ul class="dropdown-menu">
                     <li>
                        <a class="btn btn-link"
                           title="<?= Yii::t('all', 'Profile') ?>"
                           href="<?= Url::to(['admin/update', 'id' => $user->id])?>"
                        >
                          <?= Yii::t('all', 'Profile') ?>
                        </a>
                     </li>
                     <li>
                        <a class="btn btn-link" 
                           href="<?= Url::to(['site/logout']) ?>" 
                           data-method="post"
                           data-confirm="<?= Html::encode(Yii::t('admin', 'Are you sure you want to logout?'))?>"
                          >
                            <?= Yii::t('all', 'Logout') ?>     
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
                  <?php if(count($messages)): ?>
                    <?php foreach($messages as $message): ?>
                        <li>
                           <a title="" href="#">
                               <span class="subject">
                                   <span class="sender">
                                      <?= Html::encode($message->sender_name) ?>
                                   </span>
                                   <span class="sendtime">
                                      <?= $message->sendtime ?>
                                   </span>
                               </span>
                               <span class="message">
                                   <?= Html::encode($message->subject) ?>
                               </span>
                           </a>
                        </li>                         
                    <?php endforeach; ?>
                    <?php else: ?>
                    <li>
                       <a title="" href="#">
                           <span class="subject">
                               <span class="sender">&nbsp;</span>
                               <span class="sendtime">&nbsp;</span>
                           </span>
                           <span class="message">
                               您还没有任何消息哦
                           </span>
                       </a>
                    </li>
                    <?php endif; ?>
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
