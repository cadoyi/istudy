<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use core\validators\PhoneValidator;
use core\behaviors\UploadBehavior;

class UserProfile extends ActiveRecord
{

	  public $avatorImage;


	  public static function tableName()
	  {
		    return '{{%admin_profile}}';
	  }

    public function behaviors()
    {
    	return array_merge(parent::behaviors(), [
           'avator' => [
               'class' => UploadBehavior::className(),
               'attribute' => 'avatorImage',
               'targetAttribute' => 'avator',
               'path'         =>  'admin',
               'absolutePath' => '@media/admin',
           ],
    	]);
    }

	  public function rules()
	  {
        return [
            [['phone'], PhoneValidator::className() ],
            [['email'], 'email'],
            [['wechat'], 'string', 'length' => [0,255]],
            [['qq'], 'integer'],
            [['sex'], 'boolean'],
            [['note'], 'string', 'length' => [0, 255]],
            [['avatorImage'], 'image', 'extensions' => ['jpg', 'png', 'gif', 'jpeg']],
            [['phone', 'email', 'wechat', 'qq', 'avator', 'sex', 'note'], 'default', 'value' => null],
        ];
	  }

    public function scenarios()
    {
      	$default = ['phone', 'email', 'wechat', 'qq', 'avator', 'sex', 'note', 'avatorImage'];
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
             'user_id'     => Yii::t('all', 'Administrator'),
             'phone'       => Yii::t('all', 'Mobile number'),
             'email'       => Yii::t('all', 'Emergency email address'),
             'wechat'      => Yii::t('all', 'Wechat'),
             'qq'          => Yii::t('all', 'QQ'),
             'sex'         => Yii::t('all', 'Sex'),
             'note'        => Yii::t('all', 'Note'),
             'avator'      => Yii::t('all', 'Avator'),
             'avatorImage' => Yii::t('all', 'Avator'),
          ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }


    public function getAvatorUrl($full = false)
    {
        if($this->avator) {
           return Url::to('@web/media/' . $this->avator, $full);
        }
        return null;
    }
}