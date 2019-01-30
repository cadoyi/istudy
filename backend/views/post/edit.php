<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\FormContainer;
use common\models\Category;
$this->registerJsFile('@web/ckeditor/ckeditor.js', ['depends' => ['common']]);
$input = Html::getInputId($postContent, 'content');
$formid = 'edit_form';
$this->registerJsVar('contentid', $input);
$this->registerJsVar('formid', $formid);
$this->registerJs('
	var editor = CKEDITOR.replace(contentid);
    $("#" + formid).on("beforeValidate", function(event,messages,defereds) {
        var html = editor.document.getBody().getHtml();
        $("#" + contentid).text(html);
    });
');
?>
<?php
/**
 * @var $this yii\web\View
 * @var $post common\models\Post
 * @var $postContent common\models\PostContent
 */
?>
<?php $container = FormContainer::begin([
    'tabs' => [
        [
            'title' => '基本信息',
            'target' => 'post_base_info',
        ],
        [
            'title' => '内容信息',
            'target' => 'post_content_info',
        ],
        [
            'title' => '分类',
            'target' => 'post_category_info',
        ],
        [
            'title'  => '标签',
            'target' => 'post_tag_info',
        ],    
    ],
    'form' => $formid,
]) ?>
<?php $form = ActiveForm::begin([
   'id' => $formid,
]) ?>
<div id="post_base_info" class="tab-target">
	<?= $form->field($post, 'title') ?>
	<?= $form->field($post, 'url_path') ?>
	<?= $form->field($post, 'description')->textarea() ?>

</div>
<div id="post_content_info" class="tab-target">
	<?= $form->field($postContent, 'keywords')->textarea()?>
	<?= $form->field($postContent, 'description')->textarea() ?>
    <?= $form->field($postContent, 'content')->textarea() ?>
</div>
<div id="post_category_info" class="tab-target">
	<?= $form->field($post, 'category_id')
	     ->dropDownList(Category::hashOptions(), ['prompt' => '']) ?>
</div>
<div id="post_tag_info" class="tab-target">
    <h1>这里可以建立标签并选择标签</h1>
</div>


<?php ActiveForm::end() ?>
<?php FormContainer::end() ?>
