<?php

namespace common\blocks\html\builders;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\base\BaseObject;


/**
 * 构建 block
 * 
 */
class HtmlBuilder extends BaseObject implements BlockBuilderInterface
{

    /**
     * @var string HTML 标签
     */
    protected $tag;


    /**
     * @var Content 存储内容.
     */
    protected $content;


    /**
     * @var array 标签属性
     */
    protected $options = [];



    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->content = new Content();
        parent::init();
        if(!isset($this->tag)) {
            throw new InvalidConfigException('The "tag" property must be set.');
        }
    }



    /**
     * set tag property
     * 
     * @param string $tag  HTML 标签
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }



    /**
     * get tag property 
     * 
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }



    /**
     * 直接设置内容
     * 
     * @param stirng $content 
     */
    public function setContent($content)
    {
        $this->content->clear();
        $this->content->append($content);
        return $this;
    }


    /**
     * 追加内容.
     * 
     * @param string $content
     */
    public function appendContent($content)
    {
        $this->content->append($content);
        return $this;
    }


    /**
     * 在前边追加内容.
     * 
     * @param  string $content 
     * @return $this
     */
    public function prependContent($content)
    {
        $this->content->prepend($content);
        return $this;
    }


    /**
     * 增加 css 类 
     * 
     * @param array|string $class 
     */
    public function setCssClass($class)
    {
        if(!is_array($class)) {
            $class = func_get_args();
        }
        $this->options['class'] = $class;
        return $this;
    }


    /**
     * 增加 css 类
     * 
     * @param mixed $class
     */
    public function addCssClass($class)
    {
        if(!is_array($class)) {
            $class = func_get_args();
        }
        Html::addCssClass($this->options, $class);
        return $this;
    }


    /**
     * 移除 css 类
     * 
     * @param  mixed $class
     * @return $this
     */
    public function removeCssClass($class)
    {
        if(!is_array($class)) {
            $class = func_get_args();
        }
        Html::removeCssClass($this->options, $class);
        return $this;
    }



    /**
     * 增加 css 样式
     * 
     * @param array   $style     ['width' => '330px', 'height' => '0']
     * @param boolean $overwrite 是否移除旧样式
     */
    public function addCssStyle(array $style, $overwrite = true)
    {
        Html::addCssStyle($this->options, $style, $overwrite);
        return $this;
    }



    /**
     * 移除 css 样式
     * 
     * @param  mixed $attributes 
     * @return $this
     */
    public function removeCssStyle($attributes)
    {
        if(!is_array($attributes)) {
            $attributes = func_get_args();
        }
        Html::removeCssStyle($this->options, $attributes);
        return $this;
    }


    /**
     * 直接设置选项.
     * 
     * @param array   $options 选项.
     * @param boolean $merge   是否合并到现有选项.
     */
    public function setOptions(array $options, $merge = true)
    {
        if($merge) {
            $this->options = ArrayHelper::merge($this->options, $options);
        } else {
            $this->options = $options;
        }
        return $this;
    }


    /**
     * 获取选项.
     * 
     * @param  string $key      
     * @param  mixed $defaultValue 默认值
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        return ArrayHelper::getValue($this->options, $key, $defaultValue);
    }


    /**
     * 设置选项
     * 
     * @param string $key   
     * @param mixed $value
     * @return  $this 
     */
    public function set($key, $value)
    {
        ArrayHelper::setValue($this->options, $key, $value);
        return $this;
    }

    /**
     * 构建成 html
     * 
     * @return string
     */
    public function toHtml()
    {
        $content = (string) $this->content;
        return Html::tag($this->tag, $content, $this->options);
    }

    
    /**
     * 直接 echo 的时候使用
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }

}