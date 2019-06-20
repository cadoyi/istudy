<?php

namespace common\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\base\Model;

/**
 * 上传组件, 支持多文件上传.
 * 
 * 使用方法:
 *  try {
 *      $uploader = new Uploader([
 *          'name'  => 'images[0]',
 *          'path'  => 'catalog/product',
 *          'level' => 2,
 *      ]);
 *
 *      if(!$uploader->hasFile()) {
 *          return;
 *      }
 *      
 *      $uploader->addImageValidator();
 *
 *      $uploader->upload();
 *      
 *      $filename = $uploader->filename;
 *      
 *  } catch(UploadException $e) {
 *      // upload validate error message
 *  } catch(\Throwable $e) {
 *     // other error message
 *  }
 *
 * 
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class Uploader extends Model
{

    /**
     * @var boolean 是否上传多张图
     */
    public $multiple = false;


    /**
     * @var string|array 
     *     $_FILES 中的名字. 会使用 UploadedFile::getInstanceByName()
     *     如果是数组, 那么数组的第一个元素为模型,第二个元素为 name
     *     比如:
     *       'image'
     *       [$mode, 'image']
     */
    public $name;


    /**
     * @var string 文件保存的路径.
     */
    public $path;


    /**
     * @var integer 文件保存路径级别
     */
    public $level = 0;



    /**
     * @var flysystem 文件系统.
     */
    public $fs;


    /**
     * @var boolean 是否验证失败抛出异常.
     */
    public $throwError = true;


    /**
     * @var boolean 是否允许不上传.
     */
    public $allowEmpty = false;


    /**
     * @var array 验证规则.
     */
    public $validators = [];


    /**
     * @var null|array|UploadedFile 
     */
    public $file;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if(!isset($this->path)) {
            throw new InvalidConfigException('The "path" property must be set.');
        }

        if(!isset($this->name)) {
            throw new InvalidConfigException('The "name" property must be set.');
        }

        $this->initFile();
    }


    /**
     * 添加上传的错误消息
     * 
     * @param string $error 错误消息.
     */
    public function addUploadError($error)
    {
        $this->addError('file', $error);
    }


    
    /**
     * 重写父类方法, 让他支持抛出异常
     * 
     * @param string $attribute 属性名
     * @param string $error     错误消息
     */
    public function addError($attribute, $error = '')
    {
        if($this->throwError) {
            throw new UploadException($error);
        }
        parent::addError($attribute, $error);
    }


    
    /**
     * 初始化文件
     * 也就是将 $this->_file 设置成 uploadedFile 实例.
     * 
     */
    public function initFile()
    {
        $name = $this->name;
        $this->file = null;
        if($this->multiple) {
            if(is_array($name)) {
                $this->file = UploadedFile::getInstances($name[0], $name[1]);
            } else {
                $this->file = UploadedFile::getInstancesByName($name);
            }

            if(!$this->allowEmpty && empty($this->file)) {
                return $this->addUploadError('Please upload a file');
            }

            foreach($this->file as $file) {
                Yii::configure($file, [
                    'path'  => $this->path,
                    'level' => $this->level,
                    'fs'    => $this->fs,
                ]);
            }
        } else {
            if(is_array($name)) {
                $this->file = UploadedFile::getInstance($name[0], $name[1]);
            } else {
                $this->file = UploadedFile::getInstanceByName($this->name);
            }

            if(!$this->allowEmpty && empty($this->file)) {
                return $this->addUploadError('Please upload a file');
            }

            Yii::configure($this->file, [
                'path'   => $this->path,
                'level'  => $this->level,
                'fs'     => $this->fs,
            ]);
        }
    }


    /**
     * 添加验证规则.
     * 
     * @param string $type   验证类型
     * @param array  $params 验证规则参数
     */
    public function addValidator($type, $params = [])
    {
        $this->validators[] = array_merge([
            'file',
            $type,
        ], $params);

        return $this;
    }


    /**
     * 验证图片的验证器
     * 
     * @param array $params 验证参数.
     */
    public function addImageValidator($params = [])
    {
        if(empty($params)) {
            $params = [
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => 'image/*',
            ];
        }
        return $this->addValidator('image', $params);
    }



    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function rules()
    {
        return $this->validators;
    }



    /**
     * 检查是否有文件
     * 
     * @return boolean 
     */
    public function hasFile()
    {
        if($this->multiple) {
            return !empty($this->file);
        }
        return $this->file instanceof UploadedFile;
    }


    /**
     * 获取上传的文件路径和文件名.
     * 
     * @return string
     */
    public function getFilename()
    {
        if($this->multiple) {
            $filenames = [];
            foreach($this->file as $file) {
                $filenames[] = $file->getFilename();
            }
            return $filenames;
        }
        return $this->file->getFilename();
    }



    /**
     * 上传文件.
     *  注意: 这里并不会检查是否有文件.
     * 
     * @return boolean
     */
    public function upload()
    {
        if($this->validate()) {
            if($this->multiple) {
                foreach($this->file as $file) {
                    $file->upload();
                }
            } else {
                $this->file->upload();
            }
            return true;
        }
        return false;
    }


}