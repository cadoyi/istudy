<?php

namespace common\components;

use Yii;
use yii\helpers\Inflector;
use yii\base\InvalidConfigException;
use yii\base\Exception;
use League\Flysystem\Util;

/**
 * 修复 uploaded file
 *
 * 使用方式:
 *    $model->image = UploadedFile::getInstance($model, 'image');
 *    if($model->image) {
 *        $model->image->setPath('admin/user', 2);
 *    }
 *    $result = $model->image->upload();
 *    if($result === false) {
 *        throw new Exception('file uploaded error.');
 *    }
 * 
 *
 * 
 */
class UploadedFile extends \yii\web\UploadedFile
{

    /**
     * @var string 保存的路径.
     */
    protected $_path;


    /**
     * @var integer 根据文件名前缀获得的目录级别.
     */
    protected $_level = 0;


    /**
     * @var string 生成的文件名.
     */
    protected $_filename;


    /**
     * @var Filesystem 文件系统实例.
     */
    protected $_fs;


    /**
     * @var boolean 是否要根据原始文件名生成文件名.
     */
    public $keepOriginName = true;


    /**
     * @var array 生成缩略图.
     */
    public $resize = [];

    /**
     * @var integer 索引值.
     */
    protected $_index = 0;


    /**
     * 获取文件系统
     * 
     */
    public function getFs()
    {
        if(!$this->_fs) {
            $this->_fs = Yii::$app->fs;
        }
        return $this->_fs;
    }

    /**
     * 设置文件系统
     * 
     * @param Filesystem $fs 文件系统实例.
     */
    public function setFs($fs)
    {
        $this->_fs = $fs;
    }


    protected function _generateNewFilename()
    {
        list($time, $stamp) = explode('.', microtime(true));
        $middle = dechex((int) $time);
        $stamp = (int) str_pad($stamp, 4, '0');
        $last = str_pad(dechex($stamp), 4, '0', STR_PAD_LEFT);
        $prefix = str_pad(dechex(rand(0, 65535)), 4, '0', STR_PAD_LEFT);

        $filename = $prefix . $middle . $last;
        if(!empty($this->extension)) {
            $filename .=  '.' . $this->extension;
        }
        return $filename;        
    }


    protected function _generateOriginFilename()
    {
        $index = $this->_index++;
        $name = substr(Inflector::slug($this->getBaseName(), ''), 0, 16);
        if($index !== 0) {
            $name .= $index;
        }
        return $name . '.' . $this->extension;
    }


    /**
     * 生成文件名
     * 
     * @return string 生成的文件名.
     */
    public function generateFilename()
    {
        if(!$this->keepOriginName) {
            return $this->_generateNewFilename();
        }
        return $this->_generateOriginFilename();
    }




    /**
     * 设置路径.
     * 
     * @param string $path 图片的保存路径.
     * @param string $level 图片路径级别.
     */
    public function setPath($path, $level = 0)
    {
        $this->_path = Util::normalizePath($path);
        $this->_level = $level;
    }



    /**
     * 获取设置的路径.
     * 
     * @return string
     */
    public function getPath()
    {
        if(is_null($this->_path)) {
            throw new InvalidConfigException('The "path" property must be set.');
        }
        return $this->_path;
    }


    /**
     *  设置子目录级别.
     */
    public function setLevel($level)
    {
        $this->_level = (int) $level;
    }

    /**
     * 获取子目录级别
     * 
     * @return integer
     */
    public function getLevel()
    {
        return $this->_level;
    }


    /**
     * 生成文件路径和文件名.
     * 
     * @return array
     */
    public function generatePathAndFilename()
    {
        $filename = $this->generateFilename();
        $path = $this->path;
        $subPath = [];
        for($i = 0; $i < $this->level; $i++) {
            $subPath[] = substr($filename, $i, 1);
        }
        if(count($subPath) > 0) {
            $path .= '/' . implode('/', $subPath);       
        } 
        return [$path, $filename];
    }


    /**
     * 生成唯一的文件路径.
     *
     * @return string 生成的文件名和路径.
     */
    public function getFilename()
    {
        if(!$this->_filename) {
            do {
                list($path, $name) = $this->generatePathAndFilename();
                $filename = $path . '/' . $name;
            } while( $this->fs->has($filename));
            
            if(!$this->fs->has($path)) {
                $this->fs->createDir($path);
            }
            $this->_filename = $filename;
        }
        return $this->_filename;
    }



    /**
     * 上传文件
     * 
     * @return boolean
     */
    public function upload()
    {
        $stream = fopen($this->tempName, 'rb');
        $result = $this->fs->writeStream($this->getFilename(), $stream);
        if(false === $result) {
            throw new Exception('upload failed');
        }

        foreach($this->resize as $size) {
            list($width, $height) = is_array($size) ? $size : [$size, null];
            $this->fs->getUrl($this->getFilename(), $width, $height);
        }
        return $this->getFilename();
    }
}