<?php

namespace common\blocks\html\builders;

use Yii;
use yii\base\BaseObject;

/**
 * 内容块.
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class Content extends BaseObject
{

    /**
     * @var array 保存 HTML 内容.
     */
    public $contents = [];

    
    /**
     * 追加内容.
     * 
     * @param  mixed $content 
     */
    public function append($content)
    {
        if(!empty($content)) {
            $this->contents[] = $content;
        }
    }

    
    /**
     * 将内容放在前面
     * 
     * @param  mixed $content 
     */
    public function prepend($content)
    {
        if(!is_empty($content)) {
            array_unshift($content, $this->contents);
        }
    }

    
    /**
     * 清除内容.
     * 
     */
    public function clear()
    {
        $this->contents = [];
    }


    /**
     * 构建内容
     * 
     * @return string
     */
    public  function toHtml()
    {
        if(empty($this->contents)) {
            return '';
        }
        $contents = '';
        foreach($this->contents as $content) {
            $contents .= (string) $content;
        }
        return $contents;
    }

    
    /**
     * 用于保持内容.
     * 
     * @return string 
     */
    public function __toString()
    {
        return $this->toHtml();
    }

} 