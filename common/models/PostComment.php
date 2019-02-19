<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * post_comment 表
 *
 * @property integer $id 
 * @property integer $parent_id 
 * @property integer $customer_id 
 * @property string $comment 
 * @property integer $created_at 
 * @property integer $status 审核状态
 * 
 */
class PostComment extends ActiveRecord
{

    const STATUS_PENDING  = 1;
    const STATUS_REVIEWED = 2;


    public static function statusList()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'Unaudited'),
            self::STATUS_REVIEWED => Yii::t('app', 'Audited'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_comment}}';
    }



    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'comment';
    }

  
    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ]);
    }


    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules()
    {
        return [
            [['customer_id', 'parent_id', 'post_id'], 'integer'],
            [['parent_id'], 'default', 'value' => null],
            [['comment'], 'string', 'length' => [1,255]],
            [['status'], 'default', 'value' => 1],
            [['status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $default = [
            'customer_id',
            'parent_id',
            'post_id',
            'comment',
            'status',
        ];
        return [
            static::SCENARIO_DEFAULT => $default,
            static::SCENARIO_CREATE  => $default,
            static::SCENARIO_UPDATE  => $default,
        ];
    }


    /**
     * {@inheritdoc}
     * @return array
     */
    public function attributeLabels()
    {
        return [
           'id'                => 'ID',
           'customer_id'       => Yii::t('all', 'Customer'),
           'post_id'           => Yii::t('all', 'Post'),
           'parent_id'         => Yii::t('all', 'Parent comment'),
           'comment'           => Yii::t('all', 'Comment'),
           'status'            => Yii::t('all', 'Status'),
           'created_at'        => Yii::t('all', 'Created time'),
           'post_title'        => Yii::t('all', 'Post title'),
           'post_url'          => Yii::t('all', 'Post url path'),
           'customer_email'    => Yii::t('all', 'Customer email address'),
           'customer_nickname' => Yii::t('all', 'Customer nickname'),
        ];
    }


    public function switchStatus()
    {
        $this->status = ($this->status === self::STATUS_PENDING) ? self::STATUS_REVIEWED : self::STATUS_PENDING;
    }


    /**
     * 获取文章关联
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }



    /**
     * 获取客户关联
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }


    /**
     * 获取父评论
     */
    public function getParent()
    {
        if($this->parent_id) {
            return $this->hasOne(static::className(), ['id' => 'parent_id']);
        }
        return null;
    }


}