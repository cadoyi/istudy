<?php

namespace core\validators;

use Yii;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\validators\Validator;
use yii\validators\ValidationAsset;

/**
 * 验证是否是密码格式
 *
 * 在模型中可以这样使用
 *
 * public function rules()
 * {
 *     return [
 *        [ 
 *          'password', 
 *           [
 *               'class' => 'core\validators\PasswordValidator',
 *               'mixed' => 2,
 *               'message' => Yii::t('all', 'invalid password format'),
 *               其他参数
 *           ],
 *           其他参数
 *        ],
 *        [
 *            'adminPassword',
 *            'core\validators\PasswordValidator',
 *            'mixed' => 2,
 *            'message' => Yii::t('all', 'Invalid password format'), 
 *        ],
 *     ];
 * }
 *
 * 
 */
class PasswordValidator extends Validator
{

    const CASED_UPPER = 1;   // 大写字母
    const CASED_LOWER = 2;   // 小写字母
    const CASED_DIGIT = 4;   // 数字
    const CASED_OTHER = 8;   // 特殊字符

    /**
     * 强制必须包含的类型
     * 比如必须包含大写字母, 则这里可以写 'force' => [1]
     * 比如必须包含大写或者小写字母 'force' => [1|2]
     * 
     * @var array 数组
     */
    public $force = [];      // 强制包含的类型


    /**
     * 密码混合度
     * @var integer  从 1 - 4 混合度.
     */
    public $mixed = 2;


    /**
     * {@inheritdoc}
     * @return [type] [description]
     */
	public function init()
	{
		parent::init();
        if($this->message === null) {
            $this->message = Yii::t('all', "{attribute} must be combination of {mixed} or more uppercase and lowercase and digit and other character");
        }
	}


    /**
     * 验证值是否是一个手机号码
     * 
     * @param  mixed $value  用户输入的值
     * @return array|null  失败返回错误消息数组,成功返回 null
     */
	protected function validateValue($value)
	{
        $mix = $this->parseMix($value);
        if($this->checkMix($mix) && $this->checkForce($mix)) {
           return null;
        }
        return [
            $this->message,
            ['mixed' => $this->mixed],
        ];
	}

    public function checkForce($mix)
    {
        if(empty($this->force)) {
            return true;
        }

        $valid = [];
        foreach($this->force as $index => $condition) {
            $result = false;
            foreach($mix as $value) {
                if($condition & $value) {
                    $result = true;
                    break;
                }
            }
            if(!$result) {
                return false;
            }
        }
        return true;
    }

    public function checkMix($mix)
    {
        return count($mix) >= $this->mixed;
    }

    protected function parseMix($value)
    {
        $mix = [
           'lower' => preg_match('/[a-z]/', $value) ? 2 : false,
           'upper' => preg_match('/[A-Z]/', $value) ? 1 : false,
           'digit' => preg_match('/[0-9]/', $value) ? 4 : false,
           'other' => preg_match('/[^a-zA-Z0-9]/', $value) ? 8 : false,
        ];
        return array_filter($mix, function($v) { return $v; });
    }

 
    /**
     * 客户端验证规则
     * 
     * @param  yii\base\Model $model the data model being validated
     * @param  string $attribute  the attribute being validated
     * @param  yii\web\View $view  the view object going to be rendered.
     * @return string 
     */
	public function clientValidateAttribute($model, $attribute, $view)
	{
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);
        $clientOptions = Json::HtmlEncode($options);
        return '(function(value, messages, options) {
            if(options.skipOnEmpty && yii.validation.isEmpty(value)) {
                return;
            }
            var mix = {
               lower : /[a-z]/.test(value) ? 2 : false,
               upper : /[A-Z]/.test(value) ? 1 : false,
               digit : /[0-9]/.test(value) ? 4 : false,
               other : /[^a-zA-Z0-9]/.test(value) ? 8 : false
            };
            var count = 0;
            $.each(mix, function(k,v) {
                if(v === false) { 
                    delete mix[k];
                    return;
                }
                count++;
            });
            if(count >= options.mixed) {
               if(options.force.length) {
                   $.each(options.force, function(_,f) {
                       var r = false;
                       $.each(mix, function(k,v) {
                            if(f & v) {
                                r = true;
                                return false;
                            }
                       });
                       if(!r) {
                           yii.validation.addMessage(messages, options.message, value);
                           return false;
                       }
                   });
               }
               return;
            }
            yii.validation.addMessage(messages, options.message, value);
        })(value, messages, ' . $clientOptions . ');';
	}


    /**
     * 获取客户端验证器的参数.
     * 
     * @param  yii\base\Model $model   the data model
     * @param  string $attribute the attribute of the data model
     * @return array
     */
	public function getClientOptions($model, $attribute)
	{
		return [
            'message' => $this->formatMessage($this->message, [
            	'attribute' => $model->getAttributeLabel($attribute),
                'mixed' => $this->mixed,
            ]),
            'skipOnEmpty' => $this->skipOnEmpty ? 1 : 0,
            'force' => $this->force,
            'mixed' => $this->mixed,
		];
	}


}