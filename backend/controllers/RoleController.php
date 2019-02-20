<?php

namespace backend\controllers;

use Yii;
use yii\rbac\Role;
use core\helpers\Auth;
use backend\models\AuthItem;
use backend\form\RoleSearch;

class RoleController extends Controller 
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
        $auth = Yii::$app->authManager;
        $filterModel = new RoleSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->get());
        return $this->render('index', [
           'filterModel' => $filterModel,
           'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $role = new AuthItem();
        if($post = $this->post) {
            if($role->load($post) && $role->validate()) {
                $role->save(false);
                return $this->redirect(['update', 'id' => $role->name]);
            }
        }
        return $this->render('edit', [
            'role' => $role,
            'showPermission' => false,
        ]);
    }

    public function actionView($id)
    {
        $role = $this->findRole($id);
        return $this->asJson($role);
    }

    public function actionUpdate($id)
    {
        $role = $this->findRole($id);
        if($post = $this->post) {
            if($role->load($post) && $role->validate()) {
                $permissions = Yii::$app->request->post('permission', []);
                $checked = array_filter($permissions, function($value) {
                    return $value == 1;
                });
                AuthItem::getDb()->transaction(function() use ($role, $checked) {
                    $role->save(false);
                    $auth = Yii::$app->authManager;
                    // 删除旧的
                    $auth->removeChildren($role);
                    $all = $auth->getPermissions();
                    foreach($all as $name => $object) {
                        if(array_key_exists($name, $checked)) {
                            $auth->addChild($role->getItem(), $object);
                        }
                    }
                });
                
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit', [
            'role' => $role,
            'showPermission' => true,
        ]);
    }

    public function actionDelete($id)
    {
        $role = $this->findRole($id);
        AuthItem::getDb()->transaction(function() use ($role) {
            Yii::$app->authManager->remove($role->getItem());
        });
        return $this->redirect(['index']);
    }



    public function findRole($id)
    {
        $role = AuthItem::findRole()->andWhere(['name' => $id])->one();
        if(!$role) {
            return $this->notFound();
        }
        return $role;
    }


}