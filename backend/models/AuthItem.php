<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\rbac\Item;
use yii\rbac\Role;
use yii\rbac\Permission;
use common\models\ActiveRecord;

class AuthItem extends ActiveRecord
{

    public static function getDb()
    {
        return Yii::$app->authManager->db;
    }

    public static function tableName()
    {
        return Yii::$app->authManager->itemTable;
    }

    public static function findRole()
    {
        return static::find()->where(['type' => Item::TYPE_ROLE]);        
    }

    public static function findPermission()
    {
        return static::find()->where(['type' => Item::TYPE_PERMISSION]);
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'unique', 'when' => function($model, $attribute) {
                 return $model->isAttributeChanged($attribute);
            }],
            [['name'], 'string', 'max' => 64],
            [['type'], 'default', 'value' => Item::TYPE_ROLE],
            [['description'], 'default', 'value' => function() {
                return $this->name;
            }],
            [['description'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
           'name'        => Yii::t('app', 'Role name'),
           'type'        => Yii::t('app', 'Type'),
           'description' => Yii::t('app', 'Role description'),
           'rule_name'   => Yii::t('app', 'Rule name'),
           'data'        => Yii::t('app', 'addition data'),
           'created_at'  => Yii::t('app', 'Created time'),
           'updated_at'  => Yii::t('app', 'Updated time'),
        ];
    }

    public function invalidateCache()
    {
        Yii::$app->authManager->invalidateCache();
    }


    public function getItem()
    {
        $class = $this->type == Item::TYPE_PERMISSION ? Permission::className() : Role::className();
        
        if (!isset($this->data) || ($data = @unserialize(is_resource($this->data) ? stream_get_contents($this->data) : $this->data)) === false) {
            $data = null;
        }

        return new $class([
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'ruleName' => $this->rule_name,
            'data' => $data,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ]);    
    }


    public function getPermissions()
    {
        return Yii::$app->authManager->getPermissionsByRole($this->name);
    }

    public function getAllPermissions()
    {
        return Yii::$app->authManager->getPermissions();
    }

}