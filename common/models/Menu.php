<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Menu extends ActiveRecord
{

	public static function tableName()
	{
		return '{{%menu}}';
	}

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => TimestampBehavior::className(),
            'blameable' => BlameableBehavior::className(),
        ]);
    }


	public function rules()
	{
        return [
            [['title'], 'required'],
            [['title'], 'string', 'length' => [1,32]],
            [['title'], 'unique', 'when' => function($model, $attribute) {
            	return $model->isAttributeChanged($attribute);
            }],
            [['description'], 'string', 'length' => [0,255]],
        ];
	}

	public function attributeLabels()
	{
        return [
            'id' => 'ID',
            'title' => Yii::t('all', 'Menu name'),
            'description' => Yii::t('all', 'Menu description'),
            'created_at' => Yii::t('all', 'Created time'),
            'updated_at' => Yii::t('all', 'Updated time'),
            'created_by' => Yii::t('all', 'Creator'),
            'updated_by' => Yii::t('all', 'Revisor'),
        ];
	}

	public function scenarios()
	{
		$default = ['title', 'description'];
		return [
			static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_CREATE => $default,
            static::SCENARIO_UPDATE => $default,
	    ];
	}

    public function getItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_id' => 'id'])
         ->inverseOf('menu');
    }

    public function getOrderedItems()
    {
        return MenuItem::findOrderedItems($this);
    }

    public static function findByTitle($title)
    {
        return static::find()->where(['title' => $title])->one();
    }
}