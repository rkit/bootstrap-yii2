<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\traits\ModelTrait;
use app\models\User;
use app\models\UserProfile;
use app\modules\admin\models\forms\UserForm;
use app\modules\admin\models\forms\UserProfileForm;
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
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'operations' => [
                'class' => 'app\modules\admin\controllers\common\OperationsAction',
                'modelClass' => User::class,
                'operations' => [
                    'delete' => [],
                    'set-active' => ['status' => User::STATUS_ACTIVE],
                    'set-block' => ['status' => User::STATUS_BLOCKED]
                ]
            ],
            'set-active' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => User::class,
                'attributes' => ['status' => User::STATUS_ACTIVE],
            ],
            'set-block' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => User::class,
                'attributes' => ['status' => User::STATUS_BLOCKED],
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelClass' => User::class,
            ],
            'photo-upload' => [
                'class'     => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => UserProfile::class,
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
        $model = new UserForm();

        if ($id) {
            $model->setModel($this->findModel(new User, $id));
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                try {
                    $model->save();

                    Yii::$app->session->setFlash('success', Yii::t('app.msg', 'Saved successfully'));
                    return $this->asJson(['redirect' => Url::toRoute(['edit', 'id' => $model->id])]);
                } catch (\Exception $e) {
                    Yii::error($e);
                    return $this->asJson(false);
                }
            }
            return $this->asJson($this->collectErrors($model));
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionProfile($id)
    {
        $model = new UserProfileForm();
        $model->setModel($this->findModel(new UserProfile, $id));

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $data['UserProfileForm'] = $data['UserProfileForm'] + $data['UserProfile'];

            if ($model->load($data) && $model->validate()) {
                try {
                    $model->save();

                    Yii::$app->session->setFlash('success', Yii::t('app.msg', 'Saved successfully'));
                    return $this->asJson(['redirect' => Url::toRoute(['profile', 'id' => $model->user_id])]);
                } catch (\Exception $e) {
                    Yii::error($e);
                    return $this->asJson(false);
                }
            }
            return $this->asJson($this->collectErrors($model));
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }
}
