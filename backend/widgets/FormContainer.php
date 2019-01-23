<?php

namespace backend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Widget;
use yii\bootstrap\ActiveForm;

/**
 * 大体结构如下
 * <div class="row">
 *    <div class="col-xs-3">
 *        <ul class="list-group form-tab">
 *           // 标签内容.
 *        </ul>
 *    </div>
 *    <div class="col-xs-9">
 *        <form class="form" action="">
 *            <div class="col-xs-12">
 *                // buttons
 *            </div>
 *            <div class="col-xs-12">
 *                // form 内容
 *            </div>
 *        </form>
 *    </div>
 * </div>
 * 
 */
class FormContainer extends Widget
{

   /**
    * 渲染标签
    * title        : a标签的内容
    * target       : tab 的锚点,不带 #
    * active       : 是否是 active 的
    * icon         : 内容 icon
    * 
    * @var array
    */
	public $tabs = [];


	public $tabTitle = '标签页';


	public $buttons = [];

    public $buttonTemplate = "{commit} {back}";

    public $form = 'edit_form';

	public function init()
	{
		parent::init();
        if(!isset($this->options['id'])) {
            $this->options['id'] = $this->id;
        }
        $this->initButtons();
		ob_start();
		ob_implicit_flush(false);
	}

    public function initButtons()
    {
        $this->registerButton('back', [
            'tag'   => 'a',
            'href'   => Url::to(['index']),
            'title' => Yii::t('admin', 'Back'),
            'class' => 'btn btn-sm btn-primary',
        ]);

        $this->registerButton('commit', [
            'tag'         => 'button',
            'type'        => 'submit',
            'data'        =>  [
                'form'   => $this->form,
                'method' => 'post',
            ],
            'class' => 'btn btn-sm btn-primary',
        ]);
    }

    public function registerButton($name, $options = []) 
    {
         if(!isset($this->buttons[$name]) && strpos($this->buttonTemplate, '{' . $name . '}') !== false) {
            $this->buttons[$name] = array_merge([
                'content' => Yii::t('admin', ucfirst($name)),
            ], $options);
         }
    }


    public function run()
    {
        $content = ob_get_clean();
        return $this->render('form_container', ['content' => $content]);
    }


	public function checkActiveTab()
	{
		$actived = false;
		$first = false;
		foreach($this->tabs as $id => $tab) {
			if($first === false) {
				$first = $id;
			}
            if(isset($tab['active']) && $tab['active'] && $active === false) {
                $this->tabs[$id]['active'] = true;
                $actived = $id;
            } else {
            	$this->tabs[$id]['active'] = false;
            }
		}
		if($actived === false && $first !== false) {
			$this->tabs[$first]['active'] = true;
			$actived = $first;
		}
		return $actived;
	}

	public function tabs()
	{
		$actived = $this->checkActiveTab();
		$this->registerTabJs($actived);
        return $this->tabs;
	}

	public function registerTabJs($activeId)
	{

		$this->view->registerJs("\$('#tabs a').click(function(e){
           e.preventDefault();
           \$(this).tab('show');
		});");
		if($activeId !== false) {
			$tab = $this->tabs[$activeId];
			
			$selector = isset($tab['target']) ? $tab['target'] : false;
			if($selector) {
				$this->view->registerJs("\$('#{$selector}').addClass('active');");
			}
		}
		
	}

    public function isActiveButton($button)
    {
        if(isset($button['active'])) {
            $active = $button['active'];
            if(is_callable($active)) {
               $active = call_user_func($active, $this);
            }
            return $active;
        }
        return true;
    }

	public function renderButtons()
    {
        $buttons = [];
        foreach($this->buttons as $name => $button) {
            if($this->isActiveButton($button)) {
                $buttons['{' . $name . '}'] = $this->renderButton($name, $button);
            } else {
                $button['{' . $name . '}'] = '';
            }
        }
        return strtr($this->buttonTemplate, $buttons);
    }

    public function renderButton($name, $button)
    {
        $tag = ArrayHelper::remove($button, 'tag', 'button');
        ArrayHelper::remove($button, 'active');
        $content = ArrayHelper::remove($button, 'content', '');
        return Html::tag($tag, $content, $button);
    }


}
