<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\traits\ModelTrait;
use app\models\User;
use app\models\UserProfile;
use app\modules\admin\models\search\UserSearch;

class UsersController extends \yii\web\Controller
{
    use ModelTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'set-active' => ['post'],
                    'set-block' => ['post'],
                    'delete' => ['post'],
                    'operations' => ['post'],
                    'photo-upload' => ['post'],
                    'autocomplete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'operations' => [
                'class' => 'app\modules\admin\controllers\common\OperationsAction',
                'modelClass' => 'app\models\User',
                'operations' => [
                    'delete' => [],
                    'set-active' => ['status' => User::STATUS_ACTIVE],
                    'set-block' => ['status' => User::STATUS_BLOCKED]
                ]
            ],
            'set-active' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => 'app\models\User',
                'attributes' => ['status' => User::STATUS_ACTIVE],
            ],
            'set-block' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => 'app\models\User',
                'attributes' => ['status' => User::STATUS_BLOCKED],
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelClass' => 'app\models\User',
            ],
            'photo-upload' => [
                'class'     => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => 'app\models\UserProfile',
                'attribute' => 'photo',
                'inputName' => 'file',
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
            $model = $this->findModel($model, $id);
        }

        if (Yii::$app->request->isPost) {
            if ($model->isNewRecord) {
                $model->setConfirmed();
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->assignRole($model);

                Yii::$app->session->setFlash('success', Yii::t('app.messages', 'Saved successfully'));
                $urlToModel = Url::toRoute(['edit', 'id' => $model->id]);
                if (Yii::$app->request->isAjax) {
                    return $this->asJson(['redirect' => $urlToModel]);
                }
                return $this->redirect($urlToModel);
            }
            if (Yii::$app->request->isAjax) {
                return $this->asJson($this->collectErrors($model));
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'roles' => Yii::$app->authManager->getRoles()
        ]);
    }

    public function actionProfile($id)
    {
        $model = $this->findModel(new UserProfile(), $id);

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app.messages', 'Saved successfully'));
                $urlToModel = Url::toRoute(['profile', 'id' => $model->user_id]);
                if (Yii::$app->request->isAjax) {
                    return $this->asJson(['redirect' => $urlToModel]);
                }
                return $this->redirect($urlToModel);
            }
            if (Yii::$app->request->isAjax) {
                return $this->asJson($this->collectErrors($model));
            }
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    public function actionAutocomplete()
    {
        $result = [];
        if (($term = Yii::$app->request->post('term')) !== null) {
            $data = User::find()
                ->like('username', $term)
                ->asArray()
                ->limit(10)
                ->all();

            foreach ($data as $item) {
                $result[] = [
                    'text' => $item['username'],
                    'id' => $item['id']
                ];
            }
        }

        return $this->asJson($result);
    }

    private function assignRole($model)
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($model->id);

        if (!empty($model->role)) {
            $role = $auth->getRole($model->role);
            if ($role) {
                $auth->assign($role, $model->id);
            }
        }
    }
}
