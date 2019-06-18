<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\base\Component;
use yii\web\BadRequestHttpException;
use yii\imagine\Image;
use common\components\Uploader;

class Browse extends Component
{

    const ROOT_NODE_PATH = 'root';

    const SESSION_PATH_NAME = 'wysiwyg_path';


    public $extensions = ['jpg', 'jpeg', 'png', 'gif', 'ico'];


    /**
     * 排序指定的文件,这是一个回调
     *   签名: function($file1, $file2)
     *   $file1 和 $file2 是一个数组:
     *      [
     *          'name'       => '文件名称',
     *          'timestamp'  => '文件的修改时间',
     *          'path'       => '文件的相对路径',
     *      ]
     *    如果正序, 则返回 1, 倒序返回 -1 , 否则返回 0
     *    如果设置为 false, 则表示不排序
     *    如果设置为 null, 表示根据 timestamp 进行排序.
     *    
     *  
     * @var Callable 用于排序文件.
     */
    public $fileSorter;


    /**
     * 排序指定的目录,这是一个回调
     *   签名: function($dir1, $dir2)
     *   $dir1 和 $dir2 是一个数组:
     *      [
     *          'name'       => '目录名称',
     *          'timestamp'  => '目录的修改时间',
     *          'path'       => '目录的相对路径',
     *      ]
     *    如果设置为 false, 则表示不排序
     *    如果设置为 null, 表示根据 timestamp 进行排序.
     */
    public $dirSorter = false;


    /**
     * @var string  记录当前的路径.
     */
    protected $_currentPath;


    /**
     * @var boolean 是否已经获取了当前目录下的目录和文件
     */
    protected $_isLoaded = false;


    /**
     * @var array 目录列表
     */
    protected $_dirs = [];

    /**
     * @var array 文件列表
     */
    protected $_files = [];



    /**
     * 获取 fs 
     * 
     * @return fs
     */
    public function getFs()
    {
        return Yii::$app->wysiwyg;
    }



    /**
     * 将 path 进行 URI encode
     * 
     * @param  string $path  相对路径
     * @return string 
     */
    public function idEncode($path)
    {
        return strtr(base64_encode($path), '+/=', ':_-' );
    }



    /**
     * 将 URI encode 的 id 进行 decode 
     * 
     * @param  string $id   encode 的字符串
     * @return string
     */
    public function idDecode($id)
    {
        return base64_decode(strtr($id, ':_-', '+/='));
    }


    /**
     * 获取保存的路径
     * 
     * @return string
     */
    public function getSavedPath()
    {
        return Yii::$app->session->get(static::SESSION_PATH_NAME, '');
    }    


    /**
     * 保存当前请求的路径. 当用户浏览时可以重放此路径.
     * 
     * @param string $separator 路径分隔符.
     */
    public function saveCurrentPath($separator = '/')
    {
        $path = $this->getCurrentPath();
        $arr = explode($separator, $path);
        $_path = '';
        while($part = array_shift($arr)) {
            if(!isset($_partPath)) {
                $_partPath = $part;
                $_path = $this->idEncode($_partPath);
            } else {
                $_partPath .= $separator . $part;
                $_path .= $separator . $this->idEncode($_partPath);
            }

        }
        Yii::$app->session->set(static::SESSION_PATH_NAME, $_path);        
    }



    /**
     * 获取当前请求的路径.
     * 
     * @return string
     */
    public function getCurrentPath()
    {
        if($this->_currentPath === null) {
            $path = Yii::$app->request->post('node', false);
            if($path === false) {
                throw new BadRequestHttpException(Yii::t('app', 'Bad Request'));
            } elseif($path === static::ROOT_NODE_PATH) {
                $this->_currentPath = '/';
            } else {
                $this->_currentPath = $this->idDecode($path);
            }
            $this->_currentPath = ltrim($this->_currentPath, '/');
        }
        return $this->_currentPath;
    }


    /**
     * 加载当前路径下的 file 和 dir 列表
     */
    public function load()
    {
        if(!$this->_isLoaded) {
            $contents = $this->fs->listContents($this->currentPath);
            
            $_dirs = [];
            $_files = [];
            foreach($contents as $item) {
                if(!$this->isAllowed($item)) {
                    continue;
                }
                if($item['type'] === 'dir') {
                    $_dirs[] = $item;
                } else {
                    $_files[] = $item;
                }
            }
            $this->_dirs = $this->_prepareDirs($_dirs);
            $this->_files = $this->_prepareFiles($_files);
            $this->sort();
            $this->filter();
            $this->_isLoaded = true;
        }
    }


    /**
     * 获取当前路径下的目录
     * 
     * @return array
     */
    public function getDirs()
    {
        $this->load();
        return $this->_dirs;
    }


    /**
     * 获取当前目录下的文件
     * 
     * @return array
     */
    public function getFiles()
    {
        $this->load();
        return $this->_files;
    }


    /**
     * 收集目录的信息
     * 
     * @param  array $dirs 目录的绝对路径
     * @return array 收集的信息
     */
    protected function _prepareDirs($dirs)
    {
        $_dirs = [];
        foreach($dirs as $dir) {
            $_dirs[] = $this->getFolderInfo($dir);
        }
        return $_dirs;
    }


    /**
     * 收集文件信息
     * 
     * @param  array $files 文件路径数组
     * @return array 收集的文件信息
     */
    protected function _prepareFiles($files) 
    {
        $_files = [];
        foreach($files as $file) {
            $_files[] = $this->getFileInfo($file);
        }

        return $_files;
    }


    /**
     * 获取目录的详细信息
     * 
     * @param  string $folder 目录的绝对路径
     * @return array
     */
    public function getFolderInfo( $item )
    {
        if(isset($item['basename'])) {
            $basename = $item['basename'];
        } else {
            $parts = explode('/', $item['path']);
            $basename = array_pop($parts);
        }
        return [
            'id'         => $this->idEncode($item['path']),
            'label'      => $basename,
            'path'       => $item['path'],
            'timestamp'  => $item['timestamp'],
            'has_child'  => $this->_hasChild($item), 
        ];
    }


    /**
     * 获取文件的详细信息
     * 
     * @param  string $file 文件的绝对路径
     * @return array
     */
    public function getFileInfo( $item )
    {
        $info = pathinfo($item['path']);
        $dirname = $item['dirname'] ?? $info['dirname'];
        $basename = $item['basename'] ?? $info['basename'];
        $filename = $item['filename'] ?? $info['filename'];
        $extension = $item['extension'] ?? $info['extension'];

        return [ 
            'path'        => $item['path'],  //文件路径
            'node'        => $this->idEncode($dirname),  //目录 id
            'timestamp'   => $item['timestamp'],
            'name'        => $basename,
            'short_name'  => $this->getShortFilename($filename),
            'size'        => $this->humanReadableSize($item['size']),
            'extension'   => $extension,
        ];
    }


    /**
     * 将长的文件名缩略成简短的文件名
     * 
     * @param  string $filename 文件名不带扩展名的.
     * @return string
     */
    public function getShortFilename($filename, $length = 20)
    {
        if(strlen($filename) > $length) {
            $filename = substr($name, 0, $length - 3) . '...';
        }
        return $filename;
    }



    /**
     * 人类可读的文件大小
     * 
     * @param  integer $size 文件尺寸
     * @return 
     */
    public static function humanReadableSize($size, $decimals = 2)
    {
        $factor = floor((strlen($size) -1) / 3);
        $unit = ['B', 'KB', 'MB', 'GB', 'TB'];
        return sprintf("%.{$decimals}f", $size / pow(1024, $factor)) . $unit[$factor];
    }


    /**
     * 目录下是否有子目录
     * 
     * @param  string  $dir 绝对路径
     * @return boolean  
     */
    protected function _hasChild($dir)
    {
        $path = $dir['path'];
        $contents = $this->fs->listContents($path);
        foreach($contents as $item) {
            if(!$this->isAllowed($item)) {
                continue;
            }
            if($item['type'] == 'dir') {
                return true;
            }
        }
        return false;
    }


    /**
     * 对文件和目录进行排序
     * 
     * @return null
     */
    public function sort()
    {
        $callable = function($a, $b) {
            $result = $a['timestamp'] - $b['timestamp'];
            return $result > 0 ? 1 : ($result == 0 ? 0 : -1);
        };
        if(is_callable($this->fileSorter)) {
            usort($this->_files, $this->fileSorter);
        }elseif($this->fileSorter === null) {
            usort($this->_files, $callable);
        }
        if(is_callable($this->dirSorter)) {
            usort($this->_dirs, $this->dirSorter);
        } elseif($this->dirSorter === null) {
            usort($this->_dirs, $callable);
        }
    }


    public function filter()
    {
        //主要用于过滤文件. 比如不希望显示一些文件.
        foreach($this->_dirs as $index => $dir) {
            $name = basename($dir['path']);
            if(preg_match('/^\./', $name)) {
                unset($this->_dirs[$index]);
            }
        }
    }


    public function isAllowed($file)
    {
        return true;
    }




    public function isImage($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, $this->extensions);
    }


    public function getImageUrl($filename)
    {
        return $this->fs->getUrl($filename);
    }

    public function getThumbnailUrl($filename)
    {
        return $this->fs->getUrl($filename, 150);
    }


    /**
     * 创建子目录.
     * 
     * @param  string $name 目录名称
     * @return array 目录信息.
     */
    public function createFolder($name)
    {
        $path = ltrim($this->currentPath . '/' . $name, '\\/');
        if($this->fs->has($path)) {
            throw new BrowseException(Yii::t('app', 'Folder already exists'));
        }
        if(false === $this->fs->createDir($path)) {
            throw new BrowseException(Yii::t('app', 'Cannot create folder with unknown reason'));
        }
        $item = $this->fs->getMetadata($path);
        return $this->getFolderInfo($item);
    }




    /**
     * 删除文件夹
     * 
     * @return boolean
     */
    public function deleteFolder()
    {
        $path = $this->currentPath;
        if(!$this->fs->has($path)) {
           throw new BrowseException(Yii::t('app', 'Folder [' . $path . '] not exists.'));
        }
        $meta = $this->fs->getMetadata($path);
        if($meta['type'] != 'dir') {
            throw new BrowseException(Yii::t('app', 'Folder not exists.'));
        }
        if(false === $this->fs->deleteDir($path)) {
            throw new BrowseException(Yii::t('app', 'Cannot remove folder with unknown reason.'));
        }
        return true;
    }

   

    /**
     * 重命名文件
     * 
     * @param  string $oldname 旧的文件名
     * @param  string $name    新的文件名
     * @return array
     */
    public function renameFile($oldname, $name)
    {
        $path = $this->currentPath;
        $origin = $path . '/' . $oldname;
        $target = $path . '/' . $name;

        if($this->fs->has($target)) {
            throw new BrowseException(Yii::t('app', 'target name alrady exists'));
        }
        $result = $this->fs->rename($origin, $target);
        if($result === false) {
            throw new BrowseException(Yii::t('app', 'Cannot rename file with unknown reason'));
        }
        $item = $this->fs->getMetadata($target);
        return $this->getFileInfo($item);
    }


    /**
     * 移除文件
     * 
     * @param  string $name 移除的文件名
     * @return boolean
     */
    public function removeFile( $name )
    {
        $path = $this->currentPath;
        $file = $path . '/' . $name;
        if(!$this->fs->has($file)) {
            throw new BrowseException(Yii::t('app', 'File not exists' . $file));
        }
        if(false === $this->fs->delete($file)) {
            throw new BrowseException(Yii::t('app', 'Cannot remove file with unknown reason'));
        }
        return true;
    }


    /**
     * 上传文件
     *
     * @throws  UploadException
     * @throws  Exception 
     * @return array 
     */
    public function uploadFile()
    {
        $uploader = new Uploader([
            'name' => 'images[0]',
            'path' => $this->currentPath,
            'fs'   => $this->fs,
        ]);

        $uploader->addImageValidator();

        $uploader->upload();

        $filename = $uploader->getFilename();
        
        $item = $this->fs->getMetadata($filename);

        return $this->getFileInfo($item);
    }

}