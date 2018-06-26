<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\controllers\ControllerTrait;
use app\models\entity\{User, UserProfile};
use app\modules\admin\models\forms\{UserForm, UserProfileForm};
use app\modules\admin\models\search\UserSearch;

class UsersController extends \yii\web\Controller
{
    use ControllerTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => [
                    'set-active' => ['post'],
                    'set-block' => ['post'],
                    'delete' => ['post'],
                    'batch' => ['post'],
                    'photo-upload' => ['post'],
                    'autocomplete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'batch' => [
                'class' => 'app\modules\admin\actions\BatchAction',
                'modelClass' => User::class,
                'actions' => [
                    'delete' => [],
                    'set-active' => ['status' => User::STATUS_ACTIVE],
                    'set-block' => ['status' => User::STATUS_BLOCKED]
                ]
            ],
            'set-active' => [
                'class' => 'app\modules\admin\actions\UpdateAttributesAction',
                'modelClass' => User::class,
                'attributes' => ['status' => User::STATUS_ACTIVE],
            ],
            'set-block' => [
                'class' => 'app\modules\admin\actions\UpdateAttributesAction',
                'modelClass' => User::class,
                'attributes' => ['status' => User::STATUS_BLOCKED],
            ],
            'delete' => [
                'class' => 'app\modules\admin\actions\DeleteAction',
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
        $search = new UserSearch();
        $provider = $search->search(Yii::$app->request->get());
        $statuses = User::getStatuses();

        return $this->render('index', [
            'search' => $search,
            'provider' => $provider,
            'statuses' => $statuses,
            'roles' => Yii::$app->authManager->getRoles()
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = new UserForm();

        if ($id) {
            $model->setModel($this->findUser($id));
        }

        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = 'json';

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();

                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->redirect(['edit', 'id' => $model->id]);
            }
            return $this->asJsonModelErrors($model);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionProfile($id)
    {
        $model = new UserProfileForm($this->findProfile($id));

        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = 'json';

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();

                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->redirect(['profile', 'id' => $model->user_id]);
            }
            return $this->asJsonModelErrors($model);
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    /**
     * Find the model.
     * If the model is not found, then 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return Model
     * @throws NotFoundHttpException
     */
    private function findUser($id): yii\base\Model
    {
        $model = User::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }

        return $model;
    }

    /**
     * Find the model.
     * If the model is not found, then 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return Model
     * @throws NotFoundHttpException
     */
    private function findProfile($id): yii\base\Model
    {
        $model = UserProfile::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }

        return $model;
    }
}
