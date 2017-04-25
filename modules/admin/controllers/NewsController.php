<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\traits\ModelTrait;
use app\models\News;
use app\modules\admin\models\search\NewsSearch;

class NewsController extends \yii\web\Controller
{
    use ModelTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'publish' => ['post'],
                    'unpublish' => ['post'],
                    'delete' => ['post'],
                    'operations' => ['post'],
                    'text-upload' => ['post'],
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
                'modelClass' => 'app\models\News',
                'operations' => [
                    'delete' => [],
                    'set-publish' => ['status' => News::STATUS_ACTIVE],
                    'set-unpublish' => ['status' => News::STATUS_BLOCKED]
                ]
            ],
            'set-publish' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => 'app\models\News',
                'attributes' => ['status' => News::STATUS_ACTIVE],
            ],
            'set-unpublish' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => 'app\models\News',
                'attributes' => ['status' => News::STATUS_BLOCKED],
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelClass' => 'app\models\News',
            ],
            'text-upload' => [
                'class' => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => 'app\models\News',
                'attribute' => 'text',
                'inputName' => 'file',
                'resultFieldPath' => 'filelink',
            ],
            'preview-upload' => [
                'class'     => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => 'app\models\News',
                'attribute' => 'preview',
                'inputName' => 'file',
            ],
            'gallery-upload' => [
                'class'     => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => 'app\models\News',
                'attribute' => 'gallery',
                'inputName' => 'file',
            ],
        ];
    }

    public function actionIndex()
    {
        $newsSearch = new NewsSearch();
        $dataProvider = $newsSearch->search(Yii::$app->request->get());
        $statuses = News::getStatuses();

        return $this->render('index', [
            'newsSearch' => $newsSearch,
            'dataProvider' => $dataProvider,
            'statuses' => $statuses,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = new News();

        if ($id) {
            $model = $this->findModel($model, $id);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        ]);
    }
}
