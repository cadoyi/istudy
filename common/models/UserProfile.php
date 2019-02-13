<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use core\validators\PhoneValidator;
use core\behaviors\UploadedBehavior;
use core\helpers\App;

class UserProfile extends ActiveRecord
{

	  public $avatorFile;

    public $avatorDelete;


	  public static function tableName()
	  {
		    return '{{%admin_profile}}';
	  }

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

    public function scenarios()
    {
      	$default = ['phone', 'email', 'wechat', 'qq', 'avator', 'sex', 'note', 'avatorFile', 'avatorDelete'];
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
             'avatorDelete' => Yii::t('all', 'Delete image'),
          ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }


    public function getAvatorUrl($absolute = true)
    {
        if($this->avator) {
           return App::getMediaUrl('admin/' . $this->avator, $absolute);
        }
        return null;
    }
}