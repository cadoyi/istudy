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
     * row 选项
     * @var array
     */
	public $options = ["id"=> "form_container" ,'class' => 'row form-container' ];

	public $tabContainerOptions = ['class' => 'col-xs-3 tab-container'];

	public $tabOptions = [ 'id' => 'tabs', 'class' => 'list-group form-tab'];

	public $tabItemOptions = ['class' => 'list-group-item'];

   /**
    * 渲染标签
    * title        : 标题
    * options      : 数组, li 的选项
    * target       : tab 的锚点
    * linkOptions  : a 标签的选项
    * active       : 是否是 active 的
    * 
    * @var array
    */
	public $tabs = [];

	public $tabTitle = '标签页';

	public $tabTitleOptions = ['class' => 'list-group-item tab-title'];

	public $contentContainerOptions = [ 'class' => 'col-xs-9 form-content-container' ];

    public $buttonContainerOptions = [ 'class' => 'col-xs-12 text-right button-bar' ];

	public $buttons = [];

	public $backButton = [
		'name' => 'Back', 
		'class'=> 'btn btn-sm btn-primary',
		'url' => ['index'],
	];

	public $commitButton = [
		'name' => 'Commit', 
		'class'=> 'btn btn-sm btn-primary',
		'type' => 'submit',
	];

	public $buttonLayout = "{other}\n{commit}\n{back}";

    public $formContentOptions = ['class' => 'col-xs-12 form-content' ];

    public $formOptions = [ 'id' => 'edit_form', 'options' => ['class' => 'form'] ];

    public $form;

    public $t = 'admin';

	public function init()
	{
		parent::init();
		if(isset($this->options['id'])) {
			$this->id = $this->options['id'];
		}
		ob_start();
		ob_implicit_flush(false);
        echo Html::beginTag('div', $this->options);
        echo Html::beginTag('div', $this->tabContainerOptions);
        $this->renderTabs();
        echo Html::endTag('div');
        echo Html::beginTag('div', $this->contentContainerOptions);
        
        $this->form = ActiveForm::begin($this->formOptions);

        echo Html::beginTag('div', $this->buttonContainerOptions);
        $this->renderButtons();
        echo Html::endTag('div');
        echo Html::beginTag('div', $this->formContentOptions);

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

	public function renderTabs()
	{
		$actived = $this->checkActiveTab();
		$this->registerTabJs($actived);
        echo Html::beginTag('ul', $this->tabOptions);
        echo Html::beginTag('li', $this->tabTitleOptions);
        echo Html::encode($this->tabTitle);
        echo Html::endTag('li');
        foreach($this->tabs as $tab) {
            $this->renderTab($tab);
        }
        echo Html::endTag('ul');
	}

	public function renderTab($tab)
	{
        $itemOptions = ArrayHelper::remove($tab, 'options', []);
        $itemOptions = array_merge($this->tabItemOptions, $itemOptions);
        if(isset($tab['active']) && $tab['active']) {
        	Html::addCssClass($itemOptions, 'active');
        }
        echo Html::beginTag('li', $itemOptions);
        if(isset($tab['target'])) {
        	$url = '#' . $tab['target'];
        	$linkOptions = ArrayHelper::remove($tab, 'linkOptions', []);
        	$linkOptions['href'] = $url;
        	echo Html::beginTag('a', $linkOptions);
        }
        echo Html::encode($tab['title']);
        if(isset($tab['target'])) {
        	echo Html::endTag('a');
        }
        echo Html::endTag('li');
	}

	public function registerTabJs($activeId)
	{
		$this->view->registerJs("\$('#{$this->id} ul.form-tab a').click(function(e){
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

	public function renderButtons()
    {
    	$back = $this->renderButton($this->backButton);
    	$commit = $this->renderButton($this->commitButton);
    	$other = $this->renderOtherButtons();
        echo strtr($this->buttonLayout, [
            '{back}'   => $back,
            '{commit}' => $commit,
            '{other}'  => $other,
        ]);
    }

    public function renderOtherButtons()
    {
    	$other = '';
        foreach($this->buttons as $name => $button) {
            $other .= $this->renderButton($button);
        }
        return $other;
    }


    public function renderButton($button)
    {
    	if(is_string($button)) {
    		return $button;
    	}
    	if($button === false || $button === null) {
    		return '';
    	}
    	$name = ArrayHelper::remove($button, 'name');
        $type = ArrayHelper::getValue($button, 'type', false);
        $url = ArrayHelper::getValue($button, 'url', false);
        $name = Yii::t($this->t, $name);
        if($type === false) {
        	return Html::a($name, $url, $button);
        } else {
        	return Html::button($name, $button);
        }
    }

	public function run()
	{
	    echo Html::endTag('div');
	    ActiveForm::end();
        echo Html::endTag('div');
        echo Html::endTag('div');
        return ob_get_clean();
	}

}
