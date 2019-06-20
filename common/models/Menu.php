<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\queries\MenuQuery;
use core\db\ActiveRecord;


/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 */
class Menu extends ActiveRecord
{

	public static function tableName()
	{
		return '{{%menu}}';
	}


    public static function find()
    {
        return Yii::createObject(MenuQuery::class, [ get_called_class()]);
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