<?php

namespace backend\controllers;

use Yii;
use yii\rbac\Role;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;
use common\models\User;
use common\models\UserProfile;
use backend\form\AdminSearch;
use backend\form\RoleSelector;
use common\components\ComposeModel;
use common\models\services\RoleService;

/**
 * 管理员控制器
 *
 *
 * 
 */
class AdminController extends Controller
{


    /**
     * @inheritdoc
     * 
     * @return array
     */
    public function rbac()
    {
        return $this->_rbac([
            [
                'actions' => ['index', 'view'],
                'roles' => ['permission/view'],
            ],
            [
                'actions' => ['create'],
                'roles' => ['permission/create'],
            ],
            [
                'actions' => ['update'],
                'roles' => ['permission/update'],
            ],
            [
                'actions' => ['delete'],
                'roles' => ['permission/delete'],
            ],
        ]);
    }


    /**
     * 管理员列表
     * 
     */
    public function actionIndex()
    {
        $search = new AdminSearch();
        $get = Yii::$app->request->get();
        $provider = $search->search($get);
        return $this->render('index', [
           'searchModel' => $search,
           'dataProvider' => $provider,
        ]);
    }


    /**
     * 创建用户
     * 
     */
    public function actionCreate()
    {
        $this->_title('Create admin user');
        $user = new User(['scenario' => User::SCENARIO_CREATE]);
        $profile = new UserProfile(['scenario' => UserProfile::SCENARIO_CREATE]);
        $role = RoleSelector::getModel($user);

        $request = Yii::$app->request;
        if($post = $this->post) {
            $validator = new ComposeModel([
                'models' => [
                    'user'    => $user,
                    'profile' => $profile,
                    'role'    => $role,
                ],
            ]);
            if($validator->loadAndValidate($post)) {
                $trans = User::getDb()->beginTransaction();
                try {
                    $user->save(false);
                    $profile->user_id = $user->id;
                    $profile->save(false);

                    $auth = RoleService::instance(['user' => $user]);
                    $auth->filterAssigns($role->attributes);
                    $trans->commit();
                    return $this->redirect(['index']);
                } catch(\Throwable $e) {
                    $trans->rollBack();
                    throw $e;
                }
            }
        }
        $user->cleanPassword();
        return $this->render('edit', [
            'user'    => $user,
            'profile' => $profile,
            'role'    => $role,
        ]);
    }



    public function actionView($id)
    {
        $model = $this->findUser($id);
        return $this->renderView($model);
    }



    /**
     * 更新用户
     * 
     * @param  int $id  用户ID
     */
    public function actionUpdate($id)
    {
        $this->_title('Update admin user');

        $user = $this->findUser($id);
        $user->scenario =  User::SCENARIO_UPDATE;

        $profile = $user->profile;
        $profile->scenario = UserProfile::SCENARIO_UPDATE;

        $role = RoleSelector::getModel($user);

        $validator = ComposeModel::instance([
            'models' => [
                'user'    => $user,
                'profile' => $profile,
                'role'    => $role,
            ],
        ]);

        if($post = $this->post) {
            if($validator->loadAndValidate($post)) {
                $trans = User::getDb()->beginTransaction();
                try {
                    $user->save(false);
                    $profile->user_id = $user->id;
                    $profile->save(false);
                    $auth = RoleService::instance(['user' => $user]);
                    $auth->filterAssigns($role->attributes);
                    $trans->commit();
                } catch(\Throwable $e) {
                    $trans->rollBack();
                    throw $e;
                }
                return $this->redirect(['index']);
            }
        }
        $user->cleanPassword();
        return $this->render('edit', [
            'user' => $user,
            'profile' => $profile,
            'role' => $role,
        ]);
    }


    /**
     * 删除用户,并回收权限.
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionDelete($id)
    {
        $user = $this->findUser($id);
        $service = UserService::instance(['user' => $user]);
        if($user->canDelete()) {
            User::getDb()->transaction(function() use ($service) {
                $service->delete();
            });
        }
        $this->redirect(['index']);
    }



    public function findUser($id)
    {
        return $this->findModel($id, User::className(), function($query) {
            $query->with('profile');
        });
    }


}