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


class AdminController extends Controller
{

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

    public function actionCreate()
    {
        $this->_title('Create admin user');
        $user = new User(['scenario' => User::SCENARIO_CREATE]);
        $profile = new UserProfile(['scenario' => UserProfile::SCENARIO_CREATE]);
        $role = RoleSelector::getModel($user);

        $request = Yii::$app->request;
        if($post = $this->post) {
            $success = $user->load($post) &&
                $profile->load($post) && 
                $role -> load($post) &&
                $user->validate() &&
                $profile->validate() &&
                $role -> validate();
            if($success) {
                User::getDb()->transaction(function() use ($user, $profile, $role){
                    $user->save(false);
                    $profile->user_id = $user->id;
                    $profile->save(false);
                    $auth = Yii::$app->authManager;
                    foreach($role->attributes() as $roleName) {
                        if($role->$roleName) {
                            $roleObject = new Role(['name' => $roleName]);
                            $auth->assign($roleObject, $user->id);
                        }
                    }
                });
                return $this->redirect(['index']);
            }
        }
        $user->cleanPassword();
        return $this->render('edit', [
            'user'    => $user,
            'profile' => $profile,
            'role' => $role,
        ]);
    }



    public function actionView($id)
    {
        $model = $this->findUser($id);
        return $this->renderView($model);
    }

    public function actionUpdate($id)
    {
        $this->_title('Update admin user');
        $user = $this->findUser($id);
        $user->scenario =  User::SCENARIO_UPDATE;
        $profile = $user->profile;
        $profile->scenario = UserProfile::SCENARIO_UPDATE;
        $role = RoleSelector::getModel($user);

        if($post = $this->post) {
            $success = $user->load($post) &&
                $profile->load($post) &&
                $role->load($post) &&
                $user->validate() &&
                $profile->validate() &&
                $role->validate();
            if($success) {
                User::getDb()->transaction(function() use ($user, $profile, $role){
                    $user->save(false);
                    $profile->user_id = $user->id;
                    $profile->save(false);
                    $auth = Yii::$app->authManager;
                    $auth->revokeAll($user->id);
                    foreach($role->attributes() as $roleName) {
                        if($role->$roleName) {
                            $roleObject = new Role(['name' => $roleName]);
                            $auth->assign($roleObject, $user->id);
                        }
                    }
                });
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

    public function actionDelete($id)
    {
        $user = $this->findUser($id);
        if($user->canDelete()) {
            User::getDb()->transaction(function() use ($user) {
                Yii::$app->authManager->revokeAll($user->id);
                $user->profile->delete();
                $user->delete();
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