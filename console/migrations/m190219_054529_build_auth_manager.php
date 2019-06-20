<?php


use yii\db\Migration;
use backend\rules\OwnerRule;
use core\helpers\Auth;

/**
 * Class m190219_054529_build_auth_manager
 */
class m190219_054529_build_auth_manager extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 创建管理员权限.
        $admin = Auth::createRole('admin');
        $user = 1;
        Auth::manager()->assign($admin, $user);

        $permissions = Auth::createPermissions('permission');
        Auth::addChilds($admin, $permissions);

        $postPermissions = Auth::createPermissions('post', [
            'update/owner' => [
                'ruleName' => OwnerRule::className(),  
            ],
            'delete/owner' => [
                'ruleName' => OwnerRule::className(),
            ],
            'view/owner' => [
                'ruleName' => OwnerRule::className(),
            ],
        ]);
        Auth::addChilds($admin, $postPermissions);
        Auth::addChilds($postPermissions['post/update/owner'], $postPermissions['post/update']);
        Auth::addChilds($postPermissions['post/delete/owner'], $postPermissions['post/delete']);
        Auth::addChilds($postPermissions['post/view/owner'], $postPermissions['post/view']);

        $categoryPermissions = Auth::createPermissions('category', [
            'update/owner' => [
                'ruleName' => OwnerRule::className(),  
            ],
            'delete/owner' => [
                'ruleName' => OwnerRule::className(),
            ],
            'view/owner' => [
                'ruleName' => OwnerRule::className(),
            ],
        ]);
        Auth::addChilds($admin, $categoryPermissions);
        Auth::addChilds($categoryPermissions['category/update/owner'], $categoryPermissions['category/update']);
        Auth::addChilds($categoryPermissions['category/delete/owner'], $categoryPermissions['category/delete']);
        Auth::addChilds($categoryPermissions['category/view/owner'], $categoryPermissions['category/view']);

        $customerGroupPermissions = Auth::createPermissions('customer_group');
        Auth::addChilds($admin, $customerGroupPermissions);

        $customerPermissions = Auth::createPermissions('customer');
        Auth::addChilds($admin, $customerPermissions);

        $menuPermissions = Auth::createPermissions('menu');
        Auth::addChilds($admin, $menuPermissions);

        $enrollPermissions = Auth::createPermissions('enroll');
        Auth::addChilds($admin, $enrollPermissions);

        $tagPermissions = Auth::createPermissions('tag');
        Auth::addChilds($admin, $tagPermissions);

        $commentPermissions = Auth::createPermissions('comment');
        Auth::addChilds($admin, $commentPermissions);
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
