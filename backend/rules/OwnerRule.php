<?php

namespace backend\rules;

use Yii;
use yii\rbac\Rule;

class OwnerRule extends Rule
{

    public function execute($user, $item, $params)
    {
        return isset($params['model']) && $params['model']->created_by == $user;
    }
}