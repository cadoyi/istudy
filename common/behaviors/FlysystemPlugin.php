<?php

namespace common\behaviors;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Behavior;
use yii\imagine\Image;
use yii\helpers\Url;
use League\Flysystem\PluginInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FilesystemInterface;

/**
 * 作为 flysystem 的插件. 需要配合 creocoder/yii2-flysystem 和 league/flysystem 来使用.
 *
 * 插件用于提供一个扩展方法:
 *    getUrl($filename, $width, $height)
 *
 * 附加在 creocoder/yii2-flysystem 组件上:
 *
 * 比如:
 *   'fs' => [
 *       'class' => 'creocoder\flysystem\LocalFilesystem',
 *       'path' => '@medias',
 *       'as plugin' => [
 *           'class' => 'common\behaviors\FlysystemPlugin',
 *           'baseUrl' => '@mediasUrl',
 *       ],
 *   ]
 *
 *
 *  则直接可以使用:
 *     Yii::$app->fs->getUrl($filename, $width, $height);
 *    
 */
class FlysystemPlugin extends Behavior implements PluginInterface
{

    /**
     * @var string 文件系统的基本URL
     */
    public $baseUrl;


    /**
     * @var array 图片扩展名,用于检查是否是图片.
     */
    public $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'ico'];


    /**
     * @var callable 创建缩略图的回调
     *   签名:
     *      function($filesystem, $filename, $width, $height)
     */
    public $thumbnailCreator;


    /**
     * @var Flysystem 文件系统
     */
    protected $_filesystem;

 
    /**
     * @var boolean 是否已经附加了插件.
     */
    protected $_attached = false;




    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if(!isset($this->baseUrl)) {
            throw new InvalidConfigException('The "baseUrl" property must be set.');
        }
    }

    
    /**
     * @inheritdoc
     * 
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $this->attachPlugin();
    }

    /**
     * 附加行为的时候, 将自己作为插件附加到
     * 
     * @param  Flysystem $owner 
     */
    public function attachPlugin()
    {
        if(!$this->_attached && $this->owner->getFilesystem()) {
            $this->owner->getFilesystem()->addPlugin($this);
            $this->_attached = true;
        }
    }


    /**
     * 将路径转换为 URL
     * 
     * @param  string $filename 文件路径
     * @return string
     */
    protected function convertPathToUrl($filename)
    {
        return str_replace('\\', '/', $filename);
    }



    /**
     * 将 url 转换为路径.
     * 
     * @param  string  $filename 文件路径
     * @return string
     */
    protected function convertUrlToPath($filename)
    {
        return str_replace('/', DIRECTORY_SEPARATOR, $filename);
    }


    /**
     * 获取文件 URL
     * 
     * @param  string $filename 文件路径
     * @return string
     */
    protected function getUrl($filename)
    {
        $filename = $this->convertPathToUrl($filename);
        $filename = ltrim($filename, '/');
        return Url::to($this->baseUrl . '/' . $filename);
    }



    /**
     * 检查是否是 image
     * 
     * @param  string  $filename 文件名
     * @return boolean 
     */
    protected function isImage($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        return in_array($extension, $this->imageExtensions);
    }


    /**
     * 获取文件系统的 adapter
     * 
     */
    protected function getAdapter()
    {
        return $this->_filesystem->getAdapter();
    }


    /**
     * 检查文件系统是否是 local
     * 
     * @return boolean
     */
    protected function isLocal()
    {
        return $this->getAdapter() instanceof Local;
    }



    /**
     * 获取本地文件系统的绝对路径.
     * 
     * @param  string $filename 文件名
     * @return string
     */
    protected function applyPathPrefix($filename)
    {
        return $this->getAdapter()->applyPathPrefix($filename);
    }


    /**
     * 获取缩略图
     * 
     * @param  string $filename  文件名
     * @param  int|null $width   宽度
     * @param  int|null $height  高度
     * 
     * @return string 缩略后的 URL
     */
    protected function getThumbnailUrl($filename, $width, $height)
    {
        if($this->thumbnailCreator) {
            return call_user_func($this->thumbnailCreator, $this->_filesystem, $filename, $width, $height, $this);
        }

        $prefix = '.thumbs';
        $prefix .= '/w' . (int) $width;
        $prefix .= '_h' . (int) $height;

        $thumbnail = $prefix . '/' . $filename;

        if($this->isLocal()) { //本地文件系统
            if(!$this->_filesystem->has($thumbnail)) {
                $dir = dirname($thumbnail);            
                if(!$this->_filesystem->has($dir)) {
                    $this->_filesystem->createDir($dir);
                }
                $thumbnailPath = $this->applyPathPrefix($thumbnail);
                $filenamePath = $this->applyPathPrefix($filename);

                $image = Image::thumbnail($filenamePath, $width, $height);
                $image->save($thumbnailPath);
            }
            return $this->getUrl($thumbnail);
        }

        //如果不是本地文件系统,又没有设置回调创建缩略图,则下载远程文件在本地缩略后再上传到对应位置.
        if(!$this->_filesystem->has($thumbnail)) {
            $stream = $this->_filesystem->readStream($filename);
            $tempDir = sys_get_temp_dir();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $dst = $tempDir . '/' . tmpfile() . '.' . $extension;

            $image = Image::thumbnail($stream, $width, $height);
            $thumb = $image->get($extension);
            $this->_filesystem->write($thumbnail, $thumb);
        }
        return $this->getUrl($thumbnail);
        
    }


    /**
     * plugin handle
     * 
     * @return string
     */
    public function handle($filename, $width = null, $height = null)
    {
        if(!$this->isImage($filename) || (is_null($width) && is_null($height)) || !$this->_filesystem->has($filename)) {
            return $this->getUrl($filename);
        }
        return $this->getThumbnailUrl($filename, $width, $height);
    }



    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'getUrl';
    }



    /**
     * Set the Filesystem object.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->_filesystem = $filesystem;
    }

}