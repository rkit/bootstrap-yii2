<?php

namespace app\modules\admin\controllers;

use app\components\BaseController;
use app\models\User;
use app\models\UserProfile;
use app\modules\admin\models\search\UserSearch;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use Yii;

class UsersController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'activate' => ['post'],
                    'block' => ['post'],
                    'delete' => ['post'],
                    'operations' => ['post'],
                    'preview-upload' => ['post'],
                    'gallery-upload' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'operations' => [
                'class' => 'app\modules\admin\controllers\common\OperationsAction',
                'modelName' => 'app\models\User',
            ],
            'activate' => [
                'class' => 'app\modules\admin\controllers\common\ActivateAction',
                'modelName' => 'app\models\User',
            ],
            'block' => [
                'class' => 'app\modules\admin\controllers\common\BlockAction',
                'modelName' => 'app\models\User',
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelName' => 'app\models\User',
            ],
            'photo-upload' => [
                'class'     => 'app\controllers\common\UploadAction',
                'modelName' => 'app\models\UserProfile',
                'attribute' => 'photo', 
                'inputName' => 'file',
                'type'      => 'image',
            ],
        ];
    }
        
    public function actionIndex() 
    {
        $userSearch = new UserSearch();
        $dataProvider = $userSearch->search(Yii::$app->request->get());
        $statuses = User::getStatuses();
        
        return $this->render('index', [
            'userSearch' => $userSearch,
            'dataProvider' => $dataProvider,
            'statuses' => $statuses,
            'roles' => Yii::$app->authManager->getRoles()
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = new User();

        if ($id) {
            $model = $this->loadModel($model, $id);
        }
        
        $model->scenario = 'admin-edit';
        
        if (Yii::$app->request->isPost) {
            if ($model->isNewRecord) {
                $model->setConfirmed();
            }
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $auth = Yii::$app->authManager;
                $auth->revokeAll($model->id);
                
                if (!empty($model->role)) {
                    $role = $auth->getRole($model->role);
                    if ($role) {
                        $auth->assign($role, $model->id);
                    }
                }
                
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->response(['redirect' => Url::toRoute(['edit', 'id' => $model->id])]);   
            } else {
                return $this->response(['errors' => $model->getErrors(), 'prefix' => 'user-']);            
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'roles' => Yii::$app->authManager->getRoles()
        ]);
    }
    
    public function actionProfile($id)
    {
        $model = $this->loadModel(new UserProfile(), $id);

        if (Yii::$app->request->isPost) { 
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->response(['redirect' => Url::toRoute(['profile', 'id' => $model->user_id])]);   
            } else {
                return $this->response(['errors' => $model->getErrors(), 'prefix' => 'user-profile-']);            
            }
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }
}
