<?php

namespace common\models\services;

use Yii;
use yii\base\InvalidConfigException;
use common\models\User;

/**
 *
 *
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class UserService extends Service
{
    
    public $user;


    /**
     * @inheritdoc
     * 
     */
    public function init()
    {
        parent::init();
        if(!$this->user instanceof User) {
            throw new InvalidConfigException('The "user" property must be instanceof User');
        }
    }



    /**
     * 删除用户,并回收它的所有权限.
     * 
     * @return true
     */
    public function delete()
    {
        $roleService = roleService::instance(['user' => $this->user]);
        $roleService->revokeAll();
        $this->user->profile->delete();
        $this->user->delete();
        return true;
    }


}