<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\widgets\FormContainer;
use common\models\Category;
use common\models\Tag;
use core\helpers\Form;
use common\widgets\CKEditorInput;

$selectedTags = $post->postTags;

?>
<?php
    $input = Html::getInputId($post, 'content');
    $formid = 'edit_form';
    $this->registerJsVar('tags', Tag::hashOptions());
    $this->registerJsVar('postTags', ArrayHelper::getColumn($selectedTags, 'tag_id'));
    $this->registerJsVar('contentid', $input);
    $this->registerJsVar('formid', $formid);

    $this->registerJsFile('@web/js/post.js', [
        'depends' => ['common'],
    ]);

?>
<?php
/**
 * @var $this yii\web\View
 * @var $post common\models\Post
 * @var $post common\models\PostContent
 */
$this->getBlock('breadcrumbs')->add(Yii::t('app', 'Manage post'), ['index']);
?>
<?php $container = FormContainer::begin([
    'tabs' => [
        [
            'title' => '基本信息',
            'target' => 'post_base_info',
        ],
        [
            'title' => '源信息',
            'target' => 'post_meta_info',
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
    <?= $form->field($post, 'is_active')->dropDownList(Form::statusList()); ?>

</div>
<div id="post_meta_info" class="tab-target">
    <?= $form->field($post, 'meta_title') ?>
	<?= $form->field($post, 'meta_keywords')->textarea()?>
	<?= $form->field($post, 'meta_description')->textarea() ?>
</div>
<div id="post_content_info" class="tab-target">
    <?= $form->field($post, 'content')->widget(CKEditorInput::class, [
        'pluginOptions' => [
            'filebrowserBrowseUrl' => Url::to(['file/browse']),
            'customConfig' => Url::to('@web/config/ckeditor.js'),
        ],
    ]) ?>
</div>
<div id="post_category_info" class="tab-target">
	<?= $form->field($post, 'category_id')
	     ->dropDownList(Category::hashOptions(), ['prompt' => '']) ?>
</div>
<div id="post_tag_info" class="tab-target">
    <style>
        .tag-wrap { position:relative; }
        .tag-textarea { height:160px;}
        .unselected-tags { min-height:50px; }
        .selected-tags {
             height:160px; 
             position:absolute;
             left:0;
             top:0;
             z-index:2;
             cursor: text;
         }
    </style>
    <div class="form-group">
        <label>选择标签</label>
        <input id="tags_input" type="hidden" name="tags" value="{}" />
        <div id="unselected_tags" class="form-group unselected-tags"></div>
        <div class="input-gorup tag-wrap">
            <div class="form-control tag-textarea"></div>
            <div id="selected_tags" class="form-control selected-tags"></div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php FormContainer::end() ?>
