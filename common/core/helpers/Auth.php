<?php

namespace core\helpers;

use Yii;
use yii\base\Exception;

class Auth
{

    /**
     * 检查 authManager
     * 
     * @return yii\rbac\BaseManager
     */
    public static function manager()
    {
        $manager = Yii::$app->authManager;
        if(!$manager) {
            throw new Exception(Yii::t('app', 'You must config authManager component.'));
        }
        return $manager;
    }


    /**
     * 创建角色
     * 
     * @param  string $name   角色名称
     * @param  array  $params 角色的参数, key/value 数组,用于配置角色对象.
     * @return object
     */
    public static function createRole($name, $params = [])
    {
        $role = static::manager()->createRole($name);
        $params = array_merge(['description' => $name], $params);
        Yii::configure($role, $params);
        static::manager()->add($role);
        return $role;
    }


    /**
     * 创建权限
     * 
     * @param  string $name   权限名
     * @param  array  $params 权限的参数, key/value 数组,用于配置权限对象.
     * @return object
     */
    public static function createPermission($name, $params = [])
    {
        $permission = static::manager()->createPermission($name);
        $params = array_merge(['description' => $name], $params);
        Yii::configure($permission, $params);
        static::manager()->add($permission);
        return $permission;
    }


    /**
     * 批量创建权限
     *
     * 比如:
     *   $permissions = Auth::createPermissions('post', [
     *       'update/owner' => [
     *           'ruleName' => PostOwnerRule::className(),
     *           'description' => '只能更新自己的文章',
     *       ],
     *   ]);
     *   
     *   返回结果:
     *   [
     *       'post/update'  =>   权限对象,
     *       'post/delete'  =>   权限对象,
     *       'post/view'    =>   权限对象,
     *       'post/create'  =>   权限对象,
     *       'post/update/owner' => 权限对象,
     *   ]
     *
     *   也可以删除某个权限的创建,只需要将他在第二个参数上设置为 false 就可以.
     *   $permissions = Auth::createPermissions('post', ['view' => false]);
     *
     *   这样,返回的时候就不会返回 post/view 权限对象.
     * 
     * @param  string $category    权限的分类,比如 post
     * @param  array  $permissions 可以设置每个权限的参数
     * @return object
     */
    public static function createPermissions($category, $permissions = [])
    {
        $defaults = ['update' => [], 'delete' => [], 'view' => [], 'create' => []];
        $permissions = array_filter(array_merge($defaults, $permissions), function($value) {
            return $value !== false;
        });
        $objects = [];
        foreach($permissions as $suffix => $params) {
            $name = $category . '/' . $suffix;
            $objects[$name] = static::createPermission($name, $params);
        }
        return $objects;
    }



    /**
     * 批量增加子权限, 支持数组和长参数形式
     *
     * 数组形式:
     * Auth::addChilds($parent, [$child1, $child2, ...]);
     *
     * 长参数形式:
     * Auth::addChilds($parent, $child1, $child2, ...);
     * 
     * @param object $parent 父权限
     * @param object/array $childs 子权限
     */
    public static function addChilds($parent, $childs)
    {
        if(!is_array($childs)) {
            $childs = func_get_args();
            $parent = array_shift($childs); 
        }
        foreach($childs as $child) {
            static::manager()->addChild($parent, $child);
        }
    }
}