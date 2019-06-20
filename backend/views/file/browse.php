<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\widgets\Script;
use common\widgets\FileInput;

?>
<?php 
/**
 * @var  $this yii\web\View
 * @var  $CKEditor  CKEDITOR 实例
 * @var  $CKEditorFuncNum  CKEDITOR 函数序号
 * @var  $langCode 语言代码
 * 
 */
$this->registerJsFile('@web/js/browse.js', ['depends' => ['common']]);
$this->registerCssFile('@web/css/browse.css');
?>
<div id="x_tree_container">
    <div id="x_tree_node" class="folders">
        <!--  direcotry tree -->
    </div>
    <div class="folder-actions">

    </div>
    <div class="folder-files">
        <div id="file_uploader" class="file-uploader" style="display:none;">
            <?= FileInput::widget([
                'name' => 'images',
                'options' => [
                    'id' => 'uploader',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'uploadUrl'       => Url::to(['upload']),
                    'uploadExtraData' => new JsExpression('function() { 
                        return {
                            node : ui.selectedNode ? ui.selectedNode.id : null
                        }; 
                    }'),
                    'allowedFileTypes'      => 'image',
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'ico'],
                    'showClose'             => true,
                    'dropZoneEnabled'       => false,
                    'showCaption'           => true,
                    'overwriteInitial'      => false,
                    'initialPreviewAsData'  => true,
                    'fileActionSettings'    => [
                        'showRemove' => true,
                    ],
                ],
            ])?>
        </div>
        <div id="images_area" class="container-fluid">
            <p><?= Yii::t('app', 'There has no files')?></p>
        </div>
    </div>
</div>
<div id="folderMenu" class="popup-contextmenu">
    <ul class="dropdown-menu">
        <li>
            <a data-action="createFolder" tabindex="-1" href="#">
               <i class="fa fa-fw fa-folder"></i> 
               创建
           </a>
        </li>
        <li>
            <a data-action="renameFolder" tabindex="-1" href="#">
                <i class="fa fa-fw fa-file"></i>
                重命名
            </a>
        </li>
        <li>
            <a data-action="removeFolder" tabindex="-1" href="#">
                <i class="fa fa-fw fa-remove"></i>
                删除
            </a>
        </li>
    </ul>
</div>
<div id="fileMenu" class="popup-contextmenu">
    <ul class="dropdown-menu">
        <li>
            <a data-action="selectFile" tabindex="-1" href="#">
                <i class="fa fa-fw fa-hand-pointer-o"></i>
                选择
            </a></li>
        <li>
             <a data-action="renameFile" tabindex="-1" href="#">
                <i class="fa fa-fw fa-file-o"></i>
                 重命名
             </a>
         </li>
        <li>
            <a data-action="removeFile" tabindex="-1" href="#">
                <i class="fa fa-fw fa-remove"></i>
                删除
            </a>
        </li>
    </ul>    
</div>



<?php Script::begin() ?>
<script>
    
     ui = new UI({
        loadFolderUrl   : '<?= Url::to(['load-folder'], true) ?>',
        createFolderUrl : '<?= Url::to(['create-folder'], true)?>',
        renameFolderUrl : '<?= Url::to(['rename-folder'], true)?>',
        removeFolderUrl : '<?= Url::to(['delete-folder'], true)?>',
        loadFilesUrl    : '<?= Url::to(['load-files'], true) ?>',
        removeFileUrl   : '<?= Url::to(['remove-file'], true)?>',
        renameFileUrl   : '<?= Url::to(['rename-file'], true)?>'
    });
    ui.setRootNode('根目录');
    ui.render('<?= $path ?>');
    

    $('#uploader').on('fileuploaded', function( e , data, previewId, index) {
        var response = data.response;
        ui.renderImage( response );
        $('#' + previewId).remove();
    });
</script>
<?php Script::end() ?>


