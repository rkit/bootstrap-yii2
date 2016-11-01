<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\helpers\Model;
use yii\helpers\ArrayHelper;
use app\components\BaseController;
use app\models\AuthItem;
use app\modules\admin\models\search\AuthItemSearch;

class RolesController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'operations' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'operations' => [
                'class' => 'app\modules\admin\controllers\common\OperationsAction',
                'modelClass' => 'app\models\AuthItem',
                'operations' => [
                    'delete' => [],
                ]
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelClass' => 'app\models\AuthItem',
            ],
        ];
    }

    public function actionIndex()
    {
        $authItemSearch = new AuthItemSearch();
        $dataProvider = $authItemSearch->search(Yii::$app->request->get());

        return $this->render('index', [
            'authItemSearch' => $authItemSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($name = null)
    {
        $model = new AuthItem();
        $auth = Yii::$app->authManager;

        $roles = ArrayHelper::index($auth->getRoles(), 'name');
        $permissions = ArrayHelper::index($auth->getPermissions(), 'name');

        if ($name) {
            $model = $this->loadModel($model, $name);
            $model = $this->preparePermissionsToSave($model);
            $model = $this->prepareRolesToSave($model);

            unset($roles[$model->name]);
        }

        if (Yii::$app->request->isPost) {
            $model->type = \yii\rbac\Item::TYPE_ROLE;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if (!$model->isSuperUser()) {
                    $this->setRoles($model, $roles, $permissions);
                }

                Yii::$app->session->setFlash('success', Yii::t('app.messages', 'Saved successfully'));
                $urlToModel = Url::toRoute(['edit', 'name' => $model->name]);
                if (Yii::$app->request->isAjax) {
                    return $this->response(['redirect' => $urlToModel]);
                }
                return $this->redirect($urlToModel);
            }
            if (Yii::$app->request->isAjax) {
                return $this->response(Model::collectErrors($model));
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    private function setRoles($model, $roles, $permissions)
    {
        $auth = Yii::$app->authManager;

        $role = $auth->getRole($model->name);
        $auth->removeChildren($role);

        if (is_array($model->roles)) {
            foreach ($model->roles as $r) {
                $auth->addChild($role, $roles[$r]);
            }
        }

        if (is_array($model->permissions)) {
            $currPermissions = ArrayHelper::index(
                $auth->getPermissionsByRole($model->name),
                'name',
                []
            );
            foreach ($model->permissions as $permission) {
                if (!array_key_exists($permission, $currPermissions)) {
                    $auth->addChild($role, $permissions[$permission]);
                }
            }
        }
    }

    private function preparePermissionsToSave($model)
    {
        $permissions = Yii::$app->authManager->getPermissionsByRole($model->name);
        $model->permissions = ArrayHelper::index($permissions, 'name', []);
        $model->permissions = array_keys($model->permissions);

        return $model;
    }

    private function prepareRolesToSave($model)
    {
        $roles = Yii::$app->authManager->getChildren($model->name);
        $model->roles = ArrayHelper::index($roles, 'name', []);
        $model->roles = array_keys($model->roles);

        return $model;
    }
}
