<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\Exception;
use yii\db\ActiveRecord;
use common\components\UploadedFile;


/**
 * ActiveRecord 的自动上传图片 behavior
 * 注意: 当同时需要保存多个模型的时候, 需要动态的增加 behavior, 而不要提前设置好.
 *
 * 比如,在模型类内部定义一个方法:
 *    public function canUploadImage( $can = true )
 *    {
 *        if($can) {
 *            $this->attachBehavior('image', [
 *                'class' => 'common\behaviors\UploadBehavior',
 *                'path'  => 'catalog/category',
 *                'level' => 0,
 *                'attribute' => 'image',
 *                'inputAttribute' => 'imageFile',
 *            ]);
 *        } else {
 *            $this->detachBehavior('image');
 *        }
 *    }
 *
 *  在控制器中:
 *    $model = new Category();
 *    $model->canUploadImage();
 *
 *    if($model->load(Yii::$app->request->post()) && $model->save()) {
 *        $model->canUploadImage(false);
 *        ...
 *    }
 *
 *   这样,在新建更新多个模型的时候,只有一个模型的图片呗上传.
 * 
 */
class UploadBehavior extends Behavior
{


    /**
     * @var string 图片存放的路径,支持别名
     */
    public $path;

    /**
     * 
     * @var integer 图片子目录级别.
     */
    public $level = 0;


    /**
     * @var array 生成缩略图.
     */
    public $resize = [];

    /**
     * @var Flysystem 文件系统.
     */
    public $fs;


    /**
     * @var string 保存图片名的属性
     */
    public $attribute;


    /**
     * @var string 渲染的验证属性, 这个是表单输出时使用
     *     默认为 $attribute . 'File'
     */
    public $inputAttribute;


    /**
     * @var mixed $attribute 的旧的值
     */
    public $value;



    private $_file;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if($this->attribute === null) {
            throw new InvalidConfigException('{attribute} must be set.');
        }
        if($this->path === null) {
            throw new InvalidConfigException('{path} must be set.');
        }

        if($this->inputAttribute === null) {
            $this->inputAttribute = $this->attribute . 'File';
        }
    }

    /**
     * {@inheritdoc}
     * 
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'instance',
            ActiveRecord::EVENT_AFTER_INSERT    => 'insert',
            ActiveRecord::EVENT_AFTER_UPDATE    => 'update',
        ];
    }


    /**
     * 实例化 file 对象
     * 
     * @return 
     */
    public function instance($event)
    {
        if(!$this->isActive()) {
            return;
        }
        if($this->value === null) { //保存原始值,方便删除旧的值.
            $this->value = $this->owner->{$this->attribute};
        }
        
        $this->_file = UploadedFile::getInstance($this->owner, $this->inputAttribute);
        $this->owner->{$this->inputAttribute} = $this->_file;

        if($this->_file) {
            Yii::configure($this->_file, [
                'path'   => $this->path,
                'level'  => $this->level,
                'resize' => $this->resize,
                'fs'     => $this->fs,
            ]);
            $this->owner->{$this->attribute} = $this->_file->getFilename();
        }
    }


    /**
     *  插入图片
     * 
     * @return 
     */
    public function insert($event)
    {
        if(!$this->isActive()) {
            return;
        }

        if($this->_file) {
            $this->saveFile();
        }

        $this->clean();
    }


    /**
     * 更新图片
     * 
     */
    public function update($event)
    {
        if(!$this->isActive()) {
            return;
        }        
        if($this->_file) {
            $this->saveFile();
        }
        if($this->isDelete()) {
            $this->delete();
        }
        $this->clean();

    }

    /**
     * 删除旧图片
     * 
     * @return boolean
     */
    public function delete()
    {
        if($this->value && $this->_file->fs->has($this->value)) {
            return $this->_file->fs->delete($this->value);
        }
        return true;
    }


    /**
     * 保存上传的文件
     * 
     * @return true
     */
    public function saveFile()
    {
        if($this->_file) {
            $result = $this->_file->upload();
            if(false === $result) {
                throw new Exception('file upload failed');
            }
        }
        return true;
    }


    /**
     * 检查 input 属性是否为 active 属性
     * 
     * @return boolean 
     */
    public function isActive()
    {
        return $this->owner->isAttributeActive($this->inputAttribute);
    }



    /**
     * 是否删除旧的图片
     *   1. 用户替换图片
     *
     * @return boolean 
     */
    public function isDelete()
    {
        // 有旧的值并且有新的值
        if($this->value && $this->_file) {
            return true;
        }
        return false;
    }




    /**
     * 清除对象属性, 方便下一次再使用
     *
     */
    public function clean()
    {
        $this->_file = null;
        $this->value = null;
    }

}