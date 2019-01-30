<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use common\query\CustomerGroupQuery;


class CustomerGroup extends ActiveRecord
{
	const CACHE_TAG_NAME = 'CustomerGroup';
	const DEFAULT_GROUP_TAG = 'CustomerGroupDefault';


	public static function tableName()
	{
		return '{{customer_group}}';
	}

	public static function find()
	{
		return Yii::createObject(CustomerGroupQuery::className(), [
            get_called_class()
		]);
	}

	public static function findDefault()
	{
		return static::find()->cache(0, static::cacheTags(static::DEFAULT_GROUP_TAG))
		    ->filterDefault(true)
		    ->one();
	}

	public static function hashOptions()
	{
        $groups = static::find()
            -> select(['id', 'name'])
            -> cache(0, static::cacheTags(static::CACHE_TAG_NAME))
            -> all();
        return ArrayHelper::map($groups, 'id', 'name');
	}

	public function formName()
	{
		return 'group';
	}

	public function behaviors()
	{
		return array_merge(parent::behaviors(), [
            'timestamp' => TimestampBehavior::className(),
		]);
	}

	public function rules()
	{
		return [
			[['name'], 'required'],
            [['name', 'description'], 'string', 'length' => [0,255]],
            [['description'], 'default', 'value' => null],
            [['is_default'], 'default', 'value' => 0],
            [['is_default'], 'boolean'],
            [
            	['is_default'], 
                function($attribute, $params, $validator) {
                	if( $this->$attribute == 0 && 
                	    $this->getOldAttribute($attribute) == 1) {
                		$this->addError($attribute, Yii::t('all', 'You cannot cancel default group'));
                	}
                } 
            ]
		];
	}

	public function scenarios()
	{
		$default = ['name', 'description', 'is_default'];
		return [
           static::SCENARIO_DEFAULT => $default,
           static::SCENARIO_CREATE => $default,
           static::SCENARIO_UPDATE => $default,
		];
	}

	public function attributeLabels()
	{
		return [
           'id'          => 'ID',
           'name'        => Yii::t('all', 'Customer group name'),
           'description' => Yii::t('all', 'Description'),
           'is_default'  => Yii::t('all', 'Default group'),
           'created_at'  => Yii::t('all', 'Created time'),
           'updated_at'  => Yii::t('all', 'Updated time'),
		];
	}

	public function invalidateCache()
	{
		static::invalidate([
			static::CACHE_TAG_NAME,
			static::DEFAULT_GROUP_TAG,
		]);
	}

	public function canDelete()
	{
		return !$this->is_default;
	}
}