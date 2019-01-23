<?php

namespace backend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Menu as BaseMenu;

class Menu extends BaseMenu
{

   public $linkTemplate = '<a title="{label}" href="{url}">{icon} <span class="text-label">{label}</span></a>';

   public $labelTemplate = '<a title="{label}" href="#">{icon} <span class="text-label">{label}</span><span class="glyphicon glyphicon-menu-down"></span></a>';

   public $submenuTemplate = "\n<ul class=\"list-unstyled\">\n{items}\n</ul>\n";

   public $firstItemCssClass = 'first';

   public $lastItemCssClass = 'last';

   public $activateParents = true;


    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     */
    protected function renderItem($item)
    {
        $icon = '';
        if(isset($item['glyphicon'])) {
            $icon = Html::tag('span', '', ['class' => "ifont glyphicon glyphicon-{$item['glyphicon']}"]);
        } elseif(isset($item['icon'])) {
            $icon = Html::tag('span', '', ['class' => "ifont fa fa-{$item['icon']}"]);
        } elseif(isset($item['fonts'])) {
            $fonts = $item['fonts'];
            if(is_string($fonts)) {
                $fonts = preg_split('/\s+/', $fonts, 0, PREG_SPLIT_NO_EMPTY);
            }
            if(!in_array('ifont', $fonts)) {
                array_shift($fonts, 'ifont');
            }
            $icon = Html::tag('span', '', ['class' => $item['fonts']]);
        }

        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => $item['label'],
                '{icon}'  => $icon,
            ]);
        }

        $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

        return strtr($template, [
            '{label}' => $item['label'],
            '{icon}'  => $icon,
        ]);
    }

}