<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Model;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\FileHelper;


class UploadBehavior extends Behavior
{

    /**
     * @var string 模型的属性
     */
    public $attribute;

    /**
     * @var string 旧的值
     */
    public $oldValue;

    /**
     * @var boolean 是否是上传多个文件
     */
    public $multiple = false;


    /**
     * @var string 是否验证成功后保存到目录中.
     */
    public $save = true;

    /**
     * @var string 将路径保存到这个属性中.如果不设置,则保存到原始属性中.
     */
    public $targetAttribute;

    
    /**
     * @var string 保存的相对路径
     */
    public $path;

    /**
     * @var string 保存的绝对路径
     */
    public $absolutePath;


    /**
     * 回调函数,保存多个文件使用.
     * @var [type]
     */
    public $saveMultiple;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }
        $this->absolutePath = rtrim(Yii::getAlias($this->absolutePath), '/\\') . '/';
        $this->path = rtrim(Yii::getAlias($this->path), '/\\') . '/';
        if($this->path == '/' || $this->path == '//') {
            $this->path = '';
        }
    }


    /**
     * {@inheritdoc}
     * @return arary
     */
    public function events()
    {
        return [
            Model::EVENT_BEFORE_VALIDATE => 'instanceFile',
            Model::EVENT_AFTER_VALIDATE => 'saveFile',
        ];
    }

    public function attach($owner)
    {
        parent::attach($owner);
        $this->oldValue = $this->attributeValue;
    }

    public function setAttributeValue($value)
    {
        $this->owner->{$this->attribute} = $value;
    }

    public function getAttributeValue()
    {
        return $this->owner->{$this->attribute};
    }

    public function setTargetAttributeValue($value)
    {
        $attribute = $this->targetAttribute ? : $this->attribute;
        $this->owner->{$attribute} = $value;
    }

    public function getTargetAttributeValue()
    {
        $attribute = $this->targetAttribute ? : $this->attribute;
        return $this->owner->{$attribute};      
    }

    /**
     * 检查是否是 active 属性.
     * 
     * @return boolean [description]
     */
    public function isActive()
    {
        return $this->owner->isAttributeActive($this->attribute);
    }


    public function instanceFile()
    {
        if($this->isActive()) {
            if($this->multiple) {
                $this->attributeValue = UploadedFile::getInstances($this->owner, $this->attribute); 
            } else {
                $this->attributeValue = UploadedFile::getInstance($this->owner, $this->attribute);
            }
        }
        return true;
    }


    public function saveFile()
    {
        if($this->save && $this->isActive() && !$this->owner->hasErrors()) {
            // 验证成功,要保存图片
            if(!empty($this->attributeValue)) {
                if($this->multiple) {
                    $this->saveMultiple();
                } else {
                    $this->saveSingle();
                }
            }
        }
    }

    public function regenerateFileName($file) 
    {
        $random = Yii::$app->security->generateRandomString(32);
        $ext = $file->getExtension();
        return $random . '.' . $ext;
    }

    public function saveSingle()
    {
        $file = $this->attributeValue;
        $filename = $this->regenerateFileName($file);
        if(!is_dir($this->absolutePath)) {
            FileHelper::createDirectory($this->absolutePath);
        }
        $absolutePath = $this->absolutePath . $filename;
        $path = $this->path . $filename;
        if(!$file->saveAs($absolutePath)) {
            throw new \Exception('save file faild');
        }
        $this->targetAttributeValue = $path;
    }


    public function saveMultiple()
    {
        if(!is_null($this->saveMultiple)) {
            return call_user_func($this->saveMultiple, $this);
        } else {
            throw new \Exception('unkown method save multiple file');
        }
    }



}