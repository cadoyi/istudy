<?php

namespace core\behaviors;

use Yii;
use yii\base\Model;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\Exception;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

class UploadedBehavior extends Behavior
{

    /**
     * @var string 保存图片名的属性
     */
    public $attribute;


    /**
     * @var string 渲染的删除属性,只在更新的时候使用
     *      默认为 $attribute . 'Delete'
     */
    public $deleteAttribute;


    /**
     * @var string 渲染的验证属性, 这个是表单输出时使用
     *     默认为 $attribute . 'File'
     */
    public $inputAttribute;


    /**
     * @var mixed $attribute 的旧的值
     */
    public $value;


    /**
     * @var string 图片存放的路径,支持别名
     */
    public $path;



    private $_file;


    public function init()
    {
    	parent::init();
    	if($this->attribute === null) {
            throw new InvalidConfigException('{attribute} must be set.');
    	}
    	if($this->path === null) {
    		throw new InvalidConfigException('{path} must be set.');
    	}
    	if($this->deleteAttribute === null) {
    		$this->deleteAttribute = $this->attribute . 'Delete';
    	}
    	if($this->inputAttribute === null) {
    		$this->inputAttribute = $this->attribute . 'File';
    	}
    	$this->path = rtrim(Yii::getAlias($this->path), '/\\');
    	if(!FileHelper::createDirectory($this->path)){
            throw new Exception('Cannot create directory for {path} param.');
    	}
    }

    public function events()
    {
    	return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'instance',
            ActiveRecord::EVENT_AFTER_INSERT    => 'insert',
            ActiveRecord::EVENT_AFTER_UPDATE    => 'update',
    	];
    }

    public function instance()
    {
    	if(!$this->isActive()) {
    		return;
    	}

        $this->value = $this->owner->{$this->attribute};
    	$this->_file = UploadedFile::getInstance($this->owner, $this->inputAttribute);

        if($this->_file) {
        	$filename = Yii::$app->security->generateRandomString();
        	$ext = $this->_file->extension;
        	$this->owner->{$this->attribute} = $filename . '.' . $ext;

        } elseif($this->isDelete()) {
            $this->owner->{$this->attribute} = null;
        }
    }

    public function insert()
    {
        if(!$this->isActive()) {
        	return;
        }

        if($this->_file) {
        	$this->saveFile();
        }

        $this->clean();
    }

    public function update()
    {
        if(!$this->isActive()) {
        	return;
        }        
        if($this->_file) {
            $this->saveFile();
        }
        if($this->isDelete()) {
            $oldFile = $this->getFilePath($this->value);
            if(is_file($oldFile)) {
                FileHelper::unlink($oldFile);
            } 
        }
        $this->clean();

    }

    public function saveFile()
    {
    	if($this->_file) {
            $filename = $this->owner->{$this->attribute};
            $file = $this->getFilePath($filename);
            if(!$this->_file->saveAs($file)) {
                throw new Exception('file cannot be saved');
            }
    	}
    }

    public function isActive()
    {
    	return $this->owner->isAttributeActive($this->inputAttribute);
    }

    /**
     * 是否删除旧的图片
     *   1. 用户可以删除并且点击了点击了删除操作
     *   2. 用户替换图片
     *
     * @return boolean [description]
     */
    public function isDelete()
    {
        if($this->value && $this->_file) {
            return true;
        }
        if(!$this->owner->isAttributeRequired($this->inputAttribute)) {
            return $this->owner->{$this->deleteAttribute};
        }
        return false;
    }

    public function getFilePath($filename)
    {
    	return $this->path . DIRECTORY_SEPARATOR . $filename;
    }


    public function clean()
    {
        $this->_file = null;
        $this->value = null;
    }

}