<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Menu;
$request = Yii::$app->request;

$update = $menu->scenario == Menu::SCENARIO_UPDATE;
$this->registerJsVar('menuitems', $menu->items);
$this->registerJsFile('@web/js/menuitem.js', [
    'depends' => ['common'],
]);
?>
<div id="menu_edit" class="menu-edit">
	<div class="col-xs-12">
		<a class="btn btn-primary btn-sm pull-right" 
		   title="返回" 
		   href="<?= Url::to(['index'])?>"
		>返回</a>	

		<a class="btn btn-primary btn-sm pull-right" 
		   title="保存" 
		   href="#"
		   data-form="edit_form"
		>保存</a>
    </div>

	<?php $form = ActiveForm::begin(['id' => 'edit_form']) ?>
        <?= $form->field($menu, 'title') ?>
        <?= $form->field($menu, 'description') ?>
	<?php ActiveForm::end() ?>
	<div id="menu_items">
	    <?php if($update): ?>
      <div id="menu_item_buttons">
          <a class="btn btn-default btn-sm plus" href="#">
              <span class="glyphicon glyphicon-plus"></span>
          </a>
          <a class="btn btn-default btn-sm minus" href="#">
              <span class="glyphicon glyphicon-minus"></span>
          </a>
          <a class="btn btn-default btn-sm arrow-left" href="#">
              <span class="glyphicon glyphicon-arrow-left"></span>
          </a>
          <a class="btn btn-default btn-sm arrow-right" href="#">
              <span class="glyphicon glyphicon-arrow-right"></span>
          </a>
          <a class="btn btn-default btn-sm arrow-up" href="#">
              <span class="glyphicon glyphicon-arrow-up"></span>
          </a>
          <a class="btn btn-default btn-sm arrow-down" href="#">
              <span class="glyphicon glyphicon-arrow-down"></span>
          </a>
      </div>
           <div id="menu_item_list" class="disflex">
               <div id="menu_tree" class="left" style="padding-right:2rem;min-width:40%;">
           	   <ul class="list-unstyled">
           	   	    <li>
                        <a class="btn" href="#">第一层</a>  
                        <ul>
                            <li><a class="btn" href="#">第一层</a></li>
                            <li><a class="btn" href="#">第一层</a></li> 
                        </ul>
                    </li>
           	   	    <li>2</li>
           	   </ul>
           	   </div>
           	   <div id="menu_tree_item_edit" class="right flex1" style="padding:2rem;border-left: 1px solid #ddd;">
                    <form id="menu_tree_item_edit_form" 
                          action="#" 
                          method="post"
                          data-create="<?= Url::to('menu/createtree')?>"
                          data-update="<?= Url::to('menu/updatetree')?>"
                    >
                        <input type="hidden" name="<?= $request->csrfParam?>" value="<?= $request->csrfToken?>"/>
                        <div class="form-group">
                            <label for="menu_name">菜单名</label>
                            <input id="menu_name" class="form-control" type="text" name="name" />
                            <div class="help-block"></div>
                         </div>
                         <div class="form-group">
                              <label for="menu_description">菜单描述</label>
                              <textarea id="menu_description" class="form-control" name="description"></textarea>
                              <div class="help-block"></div>
                         </div>
                    </form>
                    <button id="save_menu_item" class="btn btn-success">
                        保存
                    </button>
           	   </div>
           </div>
	    <?php endif; ?>
    </div>
</div>