<?php

namespace backend\rules;

use Yii;
use yii\rbac\Rule;

class PostOwnerRule extends Rule
{

    public function execute($user, $item, $params)
    {
        return isset($params['post']) && $params['post']->created_by == $user;
    }
}