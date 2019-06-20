<?php

namespace core\web;

use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;


/**
 * 所有控制器的基类.
 *
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class Controller extends \yii\web\Controller
{
 

    /**
     * 设置视图的 title
     * 
     * @param  stirng $title  标题
     */
    protected function _title($title)
    {
    	$title = Yii::t('app', $title);
    	$this->getView()->title = $title;
    }



    /**
     * 设置视图的元关键字.
     * 
     * @param string $keywords 元关键字
     */
    protected function _keywords($keywords)
    {
    	$keywords = Yii::t('app', $keywords);
        $this->getView()->registerMetaKeywords($keywords);
    }



    /**
     * 设置视图的元描述
     * 
     * @param  string $description 元描述
     */
    protected function _description($description)
    {
    	$des = Yii::t('app', $description);
        $this->getView()->registerMetaDescription($des);
    }






    /**
     * 抛出 404 异常
     * 
     * @throws NotFoundHttpException
     */
    public function notFound()
    {
        throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
    }






    /**
     * 为客户端返回统一的 JSON 格式. 
     *
     * @param  int    $code      HTTP 状态码
     * @param  string $message   消息文本
     * @param  array  $data      返回的数据
     * @return Response  返回 yii\web\Response 对象
     * @see  asJson()
     */
    public function toJson($error, $message = '', $data = [])
    {
        if(is_array($message)) {
            $message = implode(',', $message);
        }
        $config = [
            'error'    => $error,
            'message'  => trim($message),
            'data'     => ArrayHelper::toArray($data),
        ];
        return $this->asJson($config);
    }


    /**
     * 返回 json 格式的错误信息.
     * 
     * @param  string $message 错误消息
     * @param  array  $data    附加的数据
     * @return Response
     */
    public function jsonError($message, $data = [])
    {
        return $this->toJson(true, $message, $data);
    }





    /**
     * 返回 json 格式的成功信息
     * 
     * @param  array  $data  附加的数据
     * @return Response
     */
    public function jsonSuccess($data = [])
    {
        return $this->toJson(false, 'OK', $data);
    }



}