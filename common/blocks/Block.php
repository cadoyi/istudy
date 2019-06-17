<?php

namespace common\blocks;

use Yii;
use yii\base\View;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Component;

/**
 * block 基类.
 *
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
class Block extends Component implements BlockInterface
{

    use BlockTrait;


    const BLOCK_BEHAVIOR_NAME = 'block';


    /**
     * @var string Block ID
     */
    public $id;


    /**
     * @var View|null 视图组件
     */
    protected $_view;



    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if(!isset($this->id)) {
            throw new InvalidConfigException('The "id" property must be set.');
        }

        $this->registerBlocks();
    }


    /**
     * 设置 view 组件
     * 
     * @param View $view view 组件
     */
    public function setView(View $view)
    {
        $this->_view = $view;
    }


    /**
     * 获取 view 组件
     * 
     * @return View
     */
    public function getView()
    {
        if($this->_view instanceof View) {
            return $this->_view;
        }
        if($this->owner instanceof View) {
            $this->_view = $this->owner;
            return $this->_view;
        }
        if(Yii::$app->has('view')) {
            return Yii::$app->view;
        }
        throw new Exception('The "view" property not found.');
    }




    /**
     * 输出 HTML
     * 
     * @return string
     */
    public function toHtml()
    {
        return '';
    }




    /**
     * 直接输出 HTML
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }
    
}