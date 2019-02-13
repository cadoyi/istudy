<?php

namespace backend\form;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use common\models\Menu;
use common\models\MenuItem;
use core\exception\ValidateException;

class MenuItemForm extends Model
{
    public $menu_id;
    public $title;
    public $url;
    public $level;
    public $position;
    public $childs = [];

    public $item;
    public $menu;

    public static function saveItems($jsonitems, $menu)
    {
        $_items = Json::decode($jsonitems);
        $items = static::buildItems($_items, $menu);
        static::save($items, $menu);
    }

    public static function buildItems($items, $menu)
    {
        $_items = [];
        foreach($items as $item) {
            $_item = new MenuItemForm();
            $_item->menu = $menu;
            $_item->item = new MenuItem([
                'menu_id' => $menu->id,
                'title'   => $item['title'],
                'url'     => $item['url'],
                'level'   => $item['level'],
                'position' => $item['position'],
            ]);
            
            if(isset($item['childs']) && !empty($item['childs'])) {
                $_item->childs = static::buildItems($item['childs'], $menu);
            }

            $_items[] = $_item;
        }
        return $_items;
    }

    public function saveItem()
    {
        if(false === $this->item->save()) {
            $errors = $this->item->firstErrors;
            $message = 'Cannot save item with unknown reason.';
            foreach($errors as $attribute => $error) {
                $message = "Value : \"{$this->item->$attribute}\" Error: {$error}"; 
                break;
            }
            throw new ValidateException($message);
        }
        if(!empty($this->childs)) {
            foreach($this->childs as $child) {
                $child->saveItem();
            }
        }
    }

    public function updateItem()
    {
        if(!empty($this->childs)) {
            foreach($this->childs as $child) {
                $child->item->parent_id = $this->item->id;
            }
        }
        $this->saveItem();
    }

    public static function save($items, $menu)
    {
        MenuItem::getDb()->transaction(function() use ($menu, $items) {
            // 删除以前的条目
            MenuItem::deleteAll(['menu_id' => $menu->id]);
            
            // 先保存一下获取到 id
            foreach($items as $item) {
                $item->saveItem();
            }

            foreach($items as $item) {
                $item->updateItem();
            }
        });
        MenuItem::flushMenuItemCache();
    }
}