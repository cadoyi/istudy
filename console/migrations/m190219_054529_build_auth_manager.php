<?php


use yii\db\Migration;
use backend\rules\PostOwnerRule;

/**
 * Class m190219_054529_build_auth_manager
 */
class m190219_054529_build_auth_manager extends Migration
{

    public function createRole($name, $params = [])
    {
        $auth = Yii::$app->authManager;
        $params = array_merge(['description' => $name], $params);
        $role = $auth->createRole($name);
        Yii::configure($role, $params);
        $auth->add($role);
        return $role;
    }

    public function createPermission($name, $params = [])
    {
        $auth = Yii::$app->authManager;
        $params = array_merge(['description' => $name ], $params);
        $permission = $auth->createPermission($name);
        Yii::configure($permission, $params);
        $auth->add($permission);
        return $permission;
    }

    public function createPermissions(array $names)
    {
        $objects = [];
        foreach($names as $key => $name) {
            if(is_int($key)) {
                $objects[] = $this->createPermission($name);
            } else {
                $objects[] = $this->createPermission($key, $name);
            }
        }
        return $objects;
    }

    public function addChilds($parent, $child)
    {
        if(is_array($child)) {
            foreach($child as $_child) {
                Yii::$app->authManager->addChild($parent, $_child);
            }
            return;
        }

        $objects = func_get_args();
        $parent = array_shift($objects);
        foreach($objects as $object) {
            Yii::$app->authManager->addChild($parent, $object);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // 创建 admin 角色
        // 创建 author 角色
        // 创建 post 角色
        $admin = $this->createRole('admin');
        $author = $this->createRole('author');        
        $post = $this->createRole('post');

        // admin <- post <- author 
        $this->addChilds($admin, $post);
        $this->addChilds($post, $author);

        $updatePost = $this->createPermission('post/update');
        $deletePost = $this->createPermission('post/delete');
        $createPost = $this->createPermission('post/author/create');
        $viewPost = $this->createPermission('post/author/view');
        $updateOwnPost = $this->createPermission('post/author/update', ['ruleName' => PostOwnerRule::className() ]);
        $deleteOwnPost = $this->createPermission('post/author/delete', ['ruleName' => PostOwnerRule::className() ]);
        
        $this->addChilds($post, $updatePost, $deletePost);
        $this->addChilds($author, $updateOwnPost, $deleteOwnPost, $viewPost, $createPost);
        
        $this->addChilds($updateOwnPost, $updatePost);
        $this->addChilds($deleteOwnPost, $deletePost);

        $category = $this->createRole('category');
        $this->addChilds($category, $this->createPermissions([
            'category/update',
            'category/delete',
            'category/view',
            'category/create',
        ]));

        $this->addChilds($admin, $category);


        $group = $this->createRole('customer_group');
        $this->addChilds($group, $this->createPermissions([
             'customer_group/update',
             'customer_group/delete',
             'customer_group/view',
             'customer_group/create',
        ]));
        $this->addChilds($admin, $group);

        $customer = $this->createRole('customer');
        $this->addChilds($customer, $this->createPermissions([
            'customer/update',
            'customer/delete',
            'customer/view',
            'customer/create',
        ]));

        $menu = $this->createRole('menu');
        $this->addChilds($menu, $this->createPermissions([
            'menu/update',
            'menu/create',
            'menu/delete',
            'menu/view',
        ]));
        $this->addChilds($admin, $menu);
         

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $manager = Yii::$app->authManager;
        $manager->removeAll();
    }

}
