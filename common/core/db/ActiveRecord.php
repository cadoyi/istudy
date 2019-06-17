<?php

namespace core\db;

use Yii;
use yii\db\Exception;
use yii\caching\TagDependency;

/**
 *
 *
 * @author zhangyang <zhangyangcado@qq.com>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';



    /**
     * @inheritdoc
     * 
     * @return yii\db\ActiveQuery
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::class, [ get_called_class() ]);
    }



    /**
     * 增加 create 和 update scenarios
     * 
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        if(!isset($scenarios[static::SCENARIO_CREATE])) {
            $scenarios[static::SCENARIO_CREATE] = $scenarios[static::SCENARIO_DEFAULT];
        }
        if(!isset($scenarios[static::SCENARIO_UPDATE])) {
            $scenarios[static::SCENARIO_UPDATE] = $scenarios[static::SCENARIO_DEFAULT];
        }
        return $scenarios;
    }



    /**
     * 获取主键值
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }



    /**
     * 设置主键值.
     * 
     * @param integer $id 主键值
     */
    public function setId($id)
    {
        $id = $this->primaryKey()[0];
        $this->$id = $id;
    }


    /**
     * 刷新 tag 依赖.
     * 
     * @param  mixed $tags  缓存标签
     */
    public function invalidateTag($tags)
    {
        $db = static::getDb();
        if($db->enableQueryCache) {
            $queryCache = $db->queryCache;
            if (is_string($queryCache) && Yii::$app) {
                 $cache = Yii::$app->get($queryCache, false);
            } else {
                $cache = $queryCache;
            }
            TagDependency::invalidate($cache, $tags);
        }
    }

     

    /**
     * 刷新缓存.
     * 
     */
    public function invalidateCache()
    {
         
    }




    /**
     * @inheritdoc
     * 
     * 保存之后刷新缓存
     */
    public function afterSave($insert, $changedAttributes = null)
    {
        if(!empty($changedAttributes)) {
            $this->invalidateCache();
        }
        return parent::afterSave($insert, $changedAttributes);
    }




    /**
     * @inheritdoc
     * 
     * 删除之后刷新缓存
     */
    public function afterDelete()
    {
        $this->invalidateCache();
        return parent::afterDelete();
    }



    /**
     * 覆盖父类方法, 区分验证错误和保存错误.
     *    验证错误:
     *        返回 false
     *        
     *    保存错误:
     *        抛出异常 
     * 
     * @param  boolean $runValidation 
     * @param  [type]  $attributeNames
     * @return int 影响的行数.
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        $result = parent::update($runValidation, $attributeNames);
        if($result === false) {
            if($this->hasErrors()) {
                return false;
            }
            throw new Exception('Update error with unknown reason.');
        }
        return $result;
    }



    /**
     * 覆盖父类方法, 区分验证错误和 DB 错误.
     *    验证错误:  返回 false
     *    保存错误:  抛出异常.
     * 
     * @return true
     */
    public function insert($runValidation = true, $attributes = null)
    {
        $result = parent::insert($runValidation, $attributes);
        if($result === false) {
            if($this->hasErrors()) {
                return false;
            }
            throw new Exception('Insert error with unknown reason.');
        }
        return $result;
    }




    /**
     * 删除记录
     * 
     * @return int 删除的行数.
     */
    public function delete()
    {
        $result = parent::delete();
        if($result === false) {
            throw new Exception('Delete error with unkonwn reason.');
        }
        return $result;
    }




}