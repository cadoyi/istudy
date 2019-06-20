<?php

namespace core\validators;

use Yii;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\validators\Validator;
use yii\validators\ValidationAsset;

/**
 * 验证是否是手机号码
 *
 * 在模型中可以这样使用
 *
 * public function rules()
 * {
 *     return [
 *        [ 
 *          'telephone', 
 *           [
 *               'class' => 'core\validators\PhoneValidator',
 *               'message' => Yii::t('all', 'invalid phone number'),
 *               其他参数
 *           ],
 *           其他参数
 *        ],
 *        [
 *            'addressPhone',
 *            'core\validators\PhoneValidator',
 *            'message' => Yii::t('all', 'Invalid address phone'), 
 *        ],
 *     ];
 * }
 *
 * 
 */
class PhoneValidator extends Validator
{

	public $message;

	public $pattern = '/^1\d{10}$/';


    /**
     * {@inheritdoc}
     * @return [type] [description]
     */
	public function init()
	{
		parent::init();
		if($this->message === null) {
			$this->message = Yii::t('all', '{attribute} must be a valid phone number');
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

        if(!is_string($value) || !preg_match($this->pattern, $value)) {
            return [ $this->message, []];
        } 
        return null;
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
        return "yii.validation.regularExpression(value, messages, {$clientOptions});";
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
            'pattern' => new JsExpression($this->pattern),
            'message' => $this->formatMessage($this->message, [
            	'attribute' => $model->getAttributeLabel($attribute),
            ]),
            'skipOnEmpty' => $this->skipOnEmpty ? 1 : 0,
		];
	}


}