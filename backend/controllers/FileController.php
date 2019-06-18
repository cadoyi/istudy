<?php

namespace backend\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use backend\models\Browse;

class FileController extends Controller
{

    public $layout = '/media';

    protected $_model;


    /**
     * {@inheritdoc}
     * 
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'languageParam' => 'langCode',
            ],
        ]);
    }


    public function getModel()
    {
        if(!$this->_model) {
            $this->_model = new Browse();
        }
        return $this->_model;
    }


    /**
     * 浏览文件夹并让用户可以选择图片
     * 
     * @param  string  $CKEditor        CKEDITOR 的 id 值
     * @param  integer $CKEditorFuncNum CKEDITOR 的函数序号
     * @param  string  $langCode        语言
     * @return string
     */
    public function actionBrowse($CKEditor, $CKEditorFuncNum, $langCode)
    {
        $path = $this->model->getSavedPath();
         return $this->render('browse', [
            'CKEditor'        => $CKEditor,
            'CKEditorFuncNum' => $CKEditorFuncNum,
            'langCode'        => $langCode,
            'path'            => $path,
         ]);
    }


    /**
     * 可以上传一张到多张图片
     * 
     * @return 返回 browse 界面.
     */
    public function actionUpload()
    {
        try {
            $info = $this->model->uploadFile();
            return $this->asJson([
               'name'       => $info['name'],
               'filename'   => $info['short_name'] . '.' . $info['extension'],
               'size'       => $info['size'],
               'timestamp'  => $info['timestamp'],
               'url'        => $this->model->getImageUrl($info['path']),
               'thumb-url'  => $this->model->getThumbnailUrl($info['path']),
            ]);
        } catch(\Throwable $e) {
            return $this->asJson(['error' => $e->getMessage()]);
        }
    }


    /**
     * 加载子目录树
     * 
     * @return
     */
    public function actionLoadFolder()
    {
        $dirs = $this->model->getDirs();
        $data = [];
        foreach($dirs as $dir) {
            $data[] = [
               'id'         => $dir['id'],
               'label'      => $dir['label'],
               'has_child'  => $dir['has_child'],
            ];
        }
        return $this->jsonSuccess($data);
    }


    /**
     * 新建文件夹.
     * 
     * @return Response
     */
    public function actionCreateFolder()
    {
        try {
            $folder = $this->model->createFolder(Yii::$app->request->post('name'));
            return $this->jsonSuccess([
                'id'         => $folder['id'],
                'label'      => $folder['label'],
                'has_child'  => $folder['has_child'],
            ]);
        } catch(\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }


    /**
     * 删除文件夹.
     * 
     * @return Response
     */
    public function actionDeleteFolder()
    {
        try {
            $this->model->deleteFolder();
            return $this->jsonSuccess();
        } catch(\Exception $e) {
            $this->jsonError($e->getMessage());
        }

    }

    public function actionRenameFolder()
    {
        $browse = $this->model;
        $name = Yii::$app->request->post('name');

        return $this->jsonSuccess([
           'id' => Yii::$app->request->post('node'),
           'label' => Yii::$app->request->post('name'),
           'has_child' => true,
        ]);
    }


    /**
     * 获取文件夹内的所有图片
     * 
     * @return Response
     */
    public function actionLoadFiles()
    {
        $files = $this->model->getFiles();
        $data = [];
        foreach($files as $file) {
            $data[] = [
                'name'       => $file['name'],
                'filename'   => $file['short_name'] . '.' . $file['extension'],
                'size'       => $file['size'],
                'timestamp'  => $file['timestamp'],
                'url'        => $this->model->getImageUrl($file['path']),
                'thumb-url'  => $this->model->getThumbnailUrl($file['path']),
            ];
        }
        $this->model->saveCurrentPath();
        return $this->jsonSuccess($data);
    }



    /**
     * 重命名文件
     * 
     * @return Response
     */
    public function actionRenameFile()
    {
        try {
            $name = Yii::$app->request->post('name');
            $oldname = Yii::$app->request->post('file');
            
            $file = $this->model->renameFile($oldname, $name);
            return $this->jsonSuccess([
                'name'       => $file['name'],
                'filename'   => $file['short_name'] . '.' . $file['extension'],
                'size'       => $file['size'],
                'timestamp'  => $file['timestamp'],
                'url'        => $this->model->getImageUrl($file['path']),
                'thumb-url'  => $this->model->getThumbnailUrl($file['path']),
            ]);
        } catch(\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }


    /**
     * 删除文件
     * 
     * @return Response
     */
    public function actionRemoveFile()
    {
        try {
            $this->model->removeFile(Yii::$app->request->post('name'));
            return $this->jsonSuccess();
        } catch(\Exception $e) {
            return $this->jsonError($e->getMessage());
        }
    }
}