<?php

namespace common\models;

use Yii;
use common\models\queries\MenuItemQuery;

use core\db\ActiveRecord;

/**
 * This is the model class for table "menu_item".
 *
 * @property string $id
 * @property string $parent_id
 * @property int $menu_id
 * @property string $title
 * @property string $url
 * @property int $position
 * @property int $level
 * 
 */
class MenuItem extends ActiveRecord
{
    const CACHE_TAG_ALL = 'menu_items';

    public $parent;
    public $childs = [];

	public static function tableName()
	{
		return '{{%menu_item}}';
	}


    public static function find()
    {
        return Yii::createObject(MenuItemQuery::class, [get_called_class()]);
    }


	public function rules()
	{
         return [
            [['title'], 'required'],
            [['title'], 'string', 'length' => [0, 255]],
            [['url'], 'string', 'length' => [0, 255]],
            [['url'], 'default', 'value' => '#'],
            [['level'], 'default', 'value' => 1],
            [['level'], 'integer'],
            [['position'], 'integer'],
            [['position'], 'default', 'value' => 0],
            [['menu_id'], 'required'],
            [['menu_id'], 'integer'],
            [['menu_id'], 'exist', 
                'targetClass' => Menu::className(),
                'targetAttribute' => 'id',
            ],
            [['parent_id'], 'default', 'value' => null],
            [['parent_id'], 'integer'],
            [['parent_id'], 'exist', 
                'targetClass' => static::className(),
                'targetAttribute' => 'id'
           ],
         ];
	}

	public function attributeLabels()
	{
		return [
            'id' => 'ID',
            'title' => Yii::t('all', 'Item name'),
            'url'  => Yii::t('all', 'Menu url'),
            'menu_id' => Yii::t('all', 'Menu'),
            'parent_id' => Yii::t('all', 'Parent'),
            'level' => Yii::t('all', 'Level'),
            'position' => Yii::t('all', 'Position'),
		];
	}



    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }


    public static function findOrderedItems($menu)
    {
        $items = static::find()
           -> where(['menu_id' => $menu->id])
           -> orderBy(['level' => SORT_ASC, 'position' => SORT_ASC])
           -> tagCache(static::CACHE_TAG_ALL, 3600)
           -> indexBy('id')
           -> all();

        $_result = [];
        foreach($items as $id => $item) {
            $position = $item->position;
            if($item->parent_id === null) {
                $_result[$position] = $item;
            } else {
                $parent = $items[$item->parent_id];
                $item->parent = $parent;
                $parent->childs[$position] = $item;
            }
        }
        return static::buildItems($_result);
    }

    protected static function buildItems($items)
    {
        $_items = [];
        foreach($items as $item) {
            $_item = [
               'label' => $item->title,
               'url'   => $item->url,
            ];
            if($item->childs) {
                $_item['items'] = static::buildItems($item->childs);
            }
            $_items[] = $_item;
        }
        return $_items;
    }

    public static function flushMenuItemCache()
    {
        static::invalidate([
            static::CACHE_TAG_ALL,
        ]);
    }
    
}