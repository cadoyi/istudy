<?php

namespace common\blocks\html;

use Yii;
use yii\helpers\Html as HtmlHelper;
use yii\helpers\ArrayHelper;
use common\blocks\Block;
use common\blocks\html\builders\HtmlBuilder;

/**
 *
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class Html extends Block
{
 

    /**
     * @var string builder 类.
     */
    public $builder = HtmlBuilder::class;



    /**
     * 构建 html
     * 
     * @param  string $tag HTML 标签
     * @return builder
     */
    protected function build($tag, $options = [])
    {
        $builder = is_string($this->builder) ? [ 'class' => $this->builder ] : $this->builder;
        $builder = ArrayHelper::merge($builder, [
            'tag'     => $tag,
            'options' => $options,
        ]);
        return Yii::createObject($builder);
    }

}