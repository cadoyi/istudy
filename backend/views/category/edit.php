<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\FormContainer;
use backend\widgets\ImageField;
use common\models\Category;
use core\helpers\Form;
use core\widgets\ImageInput;
?>
<?php
/**
 * @var $this yii\web\View
 * @var $category common\models\Category
 * @var $post common\models\Post
 * @var $postContent common\models\PostContent
 */
?>
<?php
    $input = Html::getInputId($category, 'content');
    $formid = 'edit_form';

    $this->registerJsFile('@web/ckeditor/ckeditor.js', [
        'depends' => ['common'],
    ]);

    $this->registerJsVar('contentid', $input);
    $this->registerJsVar('formid', $formid);
    
    $this->registerJs('
       var editor = CKEDITOR.replace(contentid, {
           customConfig : "/config/ckeditor.js",
        });
       window.ce = editor;
        $("#" + formid).on("beforeValidate", function(event,messages,defereds) {
            var html = editor.document.getBody().getHtml();
            console.log(html);
            $("#" + contentid).text(html);
            console.log(document.getElementById(contentid));
        });
    '); 
?>
<?php $container = FormContainer::begin([
    'tabs' => [
       [
           'title'  => '基本信息',
           'target' => 'category_base_info', 
       ],
       [
           'title'  => '源信息',
           'target' => 'category_meta_info',
       ],
       [
           'title'  => '分类页面',
           'target' => 'category_content_info',
       ],
    ],
    'form' => $formid,
]) ?>
<?php $form = ActiveForm::begin([
    'id' => $formid,
]) ?>
   <div id="category_base_info" class="tab-target">
       <?= $form->field($category, 'title') ?>
       <?= $form->field($category, 'description')->textarea() ?>
       <?= $form->field($category, 'url_path')?>
       <?= $form->field($category, 'parent_id')->dropDownList($category->parentOptions(), ['prompt' => '']) ?>
       <?= $form->field($category, 'imageFile')->widget(ImageInput::className(), [
           'url' => $category->getImageUrl(),
           'deleteAttribute' => 'imageDelete',
        ])?>
       <?= $form->field($category, 'is_active')->radioList(Form::statusList()) ?>
       <?= $form->field($category, 'position')?>

   </div>
   <div id="category_meta_info" class="tab-target">
       <?= $form->field($category, 'meta_title') ?>
       <?= $form->field($category, 'meta_keywords')->textarea() ?>
       <?= $form->field($category, 'meta_description')->textarea() ?>
   </div>
  <div id="category_content_info" class="tab-target">
        <?= $form->field($category, 'content')->textarea() ?>
  </div>
<?php ActiveForm::end() ?>
<?php FormContainer::end() ?>