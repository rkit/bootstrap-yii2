<?php

namespace app\modules\admin\controllers;

use app\components\BaseController;
use app\models\AuthItem;
use app\modules\admin\models\search\AuthItemSearch;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use Yii;

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
                'modelName' => 'app\models\AuthItem',
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelName' => 'app\models\AuthItem',
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
            
            $model->permissions = ArrayHelper::index($auth->getPermissionsByRole($model->name), 'name', []);
            $model->permissions = array_keys($model->permissions);
            
            $model->roles = ArrayHelper::index($auth->getChildren($model->name), 'name', []);
            $model->roles = array_keys($model->roles);
            
            unset($roles[$model->name]);
        }
        
        if (Yii::$app->request->isPost) {
            $model->type = \yii\rbac\Item::TYPE_ROLE;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if (!$model->isSuperUser()) {
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
                
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->response(['redirect' => Url::toRoute(['edit', 'name' => $model->name])]);   
            } else {
                return $this->response(['errors' => $model->getErrors(), 'prefix' => 'authitem-']);            
            }
        }
        
        return $this->render('edit', [
            'model' => $model,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
}
