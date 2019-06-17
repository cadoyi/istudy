<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use common\models\queries\UserProfileQuery;
use core\validators\PhoneValidator;
use core\behaviors\UploadedBehavior;
use core\helpers\App;
use core\db\ActiveRecord;

/**
 * This is the model class for table "admin_profile".
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $phone
 * @property string $email
 * @property string $wechat
 * @property int    $qq
 * @property string $avator
 * @property int    $sex
 * @property string $note
 *
 */
class UserProfile extends ActiveRecord
{

	public $avatorFile;

    public $avatorDelete;


    /**
     * @inheritdoc
     * 
     * @return string
     */
	public static function tableName()
	{
		return '{{%admin_profile}}';
	}


    /**
     * @inheritdoc
     * 
     * @return ActiveQuery
     */
    public static function find()
    {
        return Yii::createObject(UserProfileQuery::class, [ get_called_class()]);
    }


  
    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function behaviors()
    {
    	return array_merge(parent::behaviors(), [
           'avator' => [
               'class' => UploadedBehavior::className(),
               'attribute' => 'avator',
               'path'      => '@media/admin',
           ],
    	]);
    }



    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function rules()
    {
        return [
            [['phone'], PhoneValidator::className() ],
            [['email'], 'email'],
            [['wechat'], 'string', 'length' => [0,255]],
            [['qq'], 'integer'],
            [['sex'], 'boolean'],
            [['note'], 'string', 'length' => [0, 255]],
            [['avatorFile'], 'image', 'extensions' => ['jpg', 'png', 'gif', 'jpeg']],
            [['avatorDelete'], 'default', 'value' => 0],
            [['avatorDelete'], 'boolean'],
            [['phone', 'email', 'wechat', 'qq', 'avator', 'sex', 'note'], 'default', 'value' => null],
        ];
    }



    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function attributeLabels()
    {
          return [
             'id'          => 'ID',
             'user_id'     => Yii::t('all', 'Administrator'),
             'phone'       => Yii::t('all', 'Mobile number'),
             'email'       => Yii::t('all', 'Emergency email address'),
             'wechat'      => Yii::t('all', 'Wechat'),
             'qq'          => Yii::t('all', 'QQ'),
             'sex'         => Yii::t('all', 'Sex'),
             'note'        => Yii::t('all', 'Note'),
             'avator'      => Yii::t('all', 'Avator'),
             'avatorImage' => Yii::t('all', 'Avator'),
             'avatorDelete' => Yii::t('all', 'Delete image'),
          ];
    }


    /**
     * 查询用户
     * 
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }



    /**
     * 获取头像的 URL
     * 
     * @param  boolean $absolute 
     * @return string|null
     */
    public function getAvatorUrl($absolute = true)
    {
        if($this->avator) {
           return App::getMediaUrl('admin/' . $this->avator, $absolute);
        }
        return null;
    }


}