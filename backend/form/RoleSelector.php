<?php

namespace backend\form;

use Yii;
use yii\base\Model;
use yii\base\DynamicModel;

class RoleSelector extends Model
{

	public static function getModel($user)
	{
        $roles = Yii::$app->authManager->getRoles();
        $attributes = [];
        $assignments = $user->roles;
        foreach($roles as $name => $role) {
        	$attributes[$name] = array_key_exists($name, $assignments) ? 1 : null;
        }

        $model = new DynamicModel($attributes);
        foreach($attributes as $name => $value) {
        	$model->addRule($name, 'boolean');
        }
        return $model;
	}

}