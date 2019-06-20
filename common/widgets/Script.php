<?php

namespace common\widgets;

use Yii;
use yii\web\View;
use yii\base\Widget;


/**
 * 在页面上引入 javascript 一直以来都是最烦的事, 但是通过这个小部件可以解决.
 *
 * <?php Script::begin(); ?>
 * <script>
 *    // 这里写 js 
 * </script>
 * <?php Script::end(); ?>
 *
 *  内部会将这段内容注册, 用 $this->registerJs() 来注册.
 * 
 */
class Script extends Widget
{

    /**
     * @var integer js注册的位置.
     */
    public $position = View::POS_READY;


    /**
     * @var string|null  注册时的键,同名的键的 js 会被覆盖.
     */
    public $key;

    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        ob_start();
        ob_implicit_flush(false);
    }



    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $script = ob_get_clean();
        $content = preg_replace('/(<script\s*.*?>|<\/script>)/', '', $script);
        $this->view->registerJs($content, $this->position, $this->key);
    }
}