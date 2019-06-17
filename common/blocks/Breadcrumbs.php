<?php

namespace common\blocks;

use Yii;
use yii\widgets\Breadcrumbs as NativeBreadcrumbs;

/**
 *
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
class Breadcrumbs extends Block
{


    /**
     * @var boolean 是否包含 title
     */
    public $includeTitle = true;

    
    /**
     * @var array Breadcrumb widget 插件选项.
     */
    public $options = [];


    /**
     * @var boolean 是否已经包含了 title
     */
    protected $_titleIncluded = false;


    protected $_links = [];



    /**
     * 设置到 view 上.
     * 
     * @param string|array $item
     */
    protected function _add($item)
    {
        $this->_links[] = $item;
    }



    /**
     * 添加项目.
     * 
     * @param string|array $label
     * @param string|null  $url  
     */
    public function add($label, $url = null)
    {
        if(!is_array($label) && !is_null($url)) {
            $label = ['label' => $label, 'url' => $url];
        }
        $this->_add($label);
        return $this;
    }



    /**
     * 清除所有.
     */
    public function clear()
    {
        $this->_links = [];
    }


    
    /**
     * @inheritdoc
     */
    public function toHtml()
    {   
        if($this->includeTitle && !$this->_titleIncluded && isset($this->view->title)) {
            $this->add($this->view->title);
        }
        
        $options = array_merge($this->options, [
            'links' => $this->_links,
        ]);

        return NativeBreadcrumbs::widget($options);
    }

}