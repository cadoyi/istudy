<?php

namespace common\models;

use Yii;

class MenuItem extends ActiveRecord
{

	public static function tableName()
	{
		return '{{%menu_item}}';
	}


	public function rules()
	{
         return [
            [['title'], 'required'],
            [['title'], 'string', 'length' => [0, 255]],
            [['url'], 'string', 'length' => [0, 255]],
            [['url'], 'default', 'value' => '#'],
            [['url'], 'url'],
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
            'position' => Yii::t('all', 'Position'),
		];
	}

	public function scenarios()
	{
        $default = ['title', 'url', 'parent_id', 'menu_id', 'position'];
		return [
			static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_CREATE => $default,
            static::SCENARIO_UPDATE => $default,
	    ];
	}

    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }
}