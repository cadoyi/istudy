<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use common\models\queries\CustomerGroupQuery;
use core\db\ActiveRecord;


/**
 * This is the model class for table "customer_group".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $is_default
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Customer[] $customers
 */
class CustomerGroup extends ActiveRecord
{
	const CACHE_TAG_ALL = 'CustomerGroup';
	const CACHE_TAG_DEFAULT = 'CustomerGroupDefault';


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
		return static::find()->tagCache(static::CACHE_TAG_DEFAULT)
		    ->filterDefault(true)
		    ->one();
	}

	public static function hashOptions()
	{
        $groups = static::find()
            -> select(['id', 'name'])
            -> tagCache(static::CACHE_TAG_ALL)
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
		static::invalidateTag([
			static::CACHE_TAG_ALL,
			static::CACHE_TAG_DEFAULT,
		]);
	}

	public function canDelete()
	{
		return !$this->is_default;
	}
}