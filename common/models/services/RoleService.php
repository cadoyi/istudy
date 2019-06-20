<?php

namespace common\models\services;

use Yii;
use yii\rbac\Role;
use yii\base\InvalidConfigException;
use yii\base\InvalidArgumentException;
use yii\base\Exception;
use common\models\User;

/**
 * 用户角色服务.
 *
 * @property User $user 
 * @author  zhangyang <zhangyangcado@qq.com>
 */
class RoleService extends Service
{
  
    /**
     * @var User 用户实例
     */
    public $user;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if(!$this->user instanceof User) {
            throw new InvalidConfigException('The "user" property must be instance of User');
        }
    }


    /**
     * 获取 authManager
     * 
     * @return yii\rbac\BaseManager
     */
    public function getAuthManager()
    {
        if(!Yii::$app->get('authManager')) {
            throw new InvalidConfigException('The "authManager" components must be configure.' );
        }
        return Yii::$app->authManager;
    }


    /**
     * 将他转换为正常的 role 
     * 
     * @param  mixed $role  角色
     * @return Role
     */
    public function ensureRole($role)
    {
        if(is_string($role)) {
            return new Role(['name' => $role]);
        }
        if($role instanceof Role) {
            return $role;
        }
        throw new InvalidArgumentException('Argument role : '.$role .' invalid');
    }




    /**
     * 分配角色
     * 
     * @param  mixed $role  角色
     * @return boolean
     */
    public function assign($role)
    {
        $role = $this->ensureRole($role);
        $this->authManager->assign($role, $this->user->id);
    }



    /**
     * 分配多个角色.
     * 
     * @param  array   $roles     角色数组.
     * @param  boolean $overwrite 是否覆盖已有的角色.
     * @return true
     */
    public function assigns($roles, $overwrite = true)
    {
        if($overwrite) {
            $this->revokeAll();
        }
        foreach($roles as $role) {
            $this->assign($role);
        }
    }


    /**
     * 过滤后分配角色.
     * 
     * @param  array   $roles     需要过滤的角色数组.
     *     比如: 
     *     [
     *         'roleName1',           //保留
     *         'roleName2' => false,  //去掉
     *         'roleName3' => true,   //保留
     *         'roleName4' => function(roleName4) { return boolean; } //依赖于返回值.
     *     ],
     *     是否去掉,是根据是否为 empty() 来决定的.
     *     
     * @param  boolean $overwrite 是否覆盖
     * @return boolean
     */
    public function filterAssigns($roles, $overwrite = true)
    {
        if($overwrite) {
            $this->revokeAll();
        }
        $roleNames = [];
        foreach($roles as $name => $value) {
            if(is_numeric($name)) {
                $roleNames[] = $value;
            } else {
                if(is_callable($value)) {
                    $value = call_user_func($value, $name);
                }
                if(!empty($value)) {
                    $roleNames[] = $name;
                }
            }
        }
        return $this->assigns($roleNames);
    }




    /**
     * 回收所有角色
     * 
     * @return boolean
     */
    public function revokeAll()
    {
        $this->authManager->revokeAll($this->user->id);
    }



    /**
     * 回收单个角色.
     * 
     * @param  mixed $role  角色名
     * @return boolean
     */
    public function revoke($role)
    {
        $role = $this->ensureRole($role);
        $this->authManager->revoke($role, $this->user->id);
    }

}