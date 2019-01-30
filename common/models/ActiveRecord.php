<?php

namespace common\models;

use Yii;
use yii\caching\TagDependency;
use common\query\ActiveQuery;

class ActiveRecord extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';


    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [ get_called_class() ]);
    }


    /**
     * get primary key.
     */
    public function getId()
    {
        return $this->getPrimaryKey(false);
    }


    /**
     * set primary key
     * @param  integer $id  ID 的值.
     */
    public function setId($id)
    {
        $keys = $this->primaryKey();
        $pk = $keys[0];
        $this->$pk = $id;
    }


    /**
     * 允许 form 输出的字段
     * @return array
     */
    public function formFields()
    {
        return $this->attributes();
    }


    /**
     * 设置 tag 依赖
     * @param  string|array $tags 缓存 tag 标签
     * @return yii\caching\TagDependency
     */
    public static function cacheTags($tags)
    {
        return new TagDependency(['tags' => $tags]);
    }

    /**
     * 过期 tag 
     * @param  string|array $tags 需要过期的 tags
     */
    public static function invalidate($tags)
    {
        $cache = Yii::$app->cache;
        TagDependency::invalidate($cache, $tags);
    }


    public function afterSave($insert, $changedAttributes = null)
    {
        if($this->hasMethod('invalidateCache')) {
            $this->invalidateCache();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        if($this->hasMethod('invalidateCache')) {
            $this->invalidateCache();
        }
        parent::afterDelete();
    }
}