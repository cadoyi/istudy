<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AjaxFilter;
use common\models\User;
use common\models\UserProfile;
use backend\form\AdminSearch;


class AdminController extends Controller
{

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
        $user = new User(['scenario' => User::SCENARIO_CREATE]);
        $profile = new UserProfile(['scenario' => UserProfile::SCENARIO_CREATE]);

        $request = Yii::$app->request;
        if($post = $this->post) {
            $success = $user->load($post) &&
                $profile->load($post) && 
                $user->validate() &&
                $profile->validate();
            if($success) {
                User::getDb()->transaction(function() use ($user, $profile){
                    $user->save(false);
                    $profile->user_id = $user->id;
                    $profile->save(false);
                });
                return $this->redirect(['index']);
            }
        }
        $user->cleanPassword();
        return $this->render('edit', [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }



    public function actionView($id)
    {
        $model = $this->findUser($id);
        return $this->renderView($model);
    }

    public function actionUpdate($id)
    {
        $user = $this->findUser($id);
        $user->scenario =  User::SCENARIO_UPDATE;
        $profile = $user->profile;
        $profile->scenario = UserProfile::SCENARIO_UPDATE;

        if($post = $this->post) {
            $success = $user->load($post) &&
                $profile->load($post) &&
                $user->validate() &&
                $profile->validate();
            if($success) {
                User::getDb()->transaction(function() use ($user, $profile){
                    $user->save(false);
                    $profile->user_id = $user->id;
                    $profile->save(false);
                });
                return $this->redirect(['index']);
            }
        }
        $user->cleanPassword();
        return $this->render('edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function actionDelete($id)
    {
        $user = $this->findUser($id);
        if($user->canDelete()) {
            User::getDb()->transaction(function() use ($user) {
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