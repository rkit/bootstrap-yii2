<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\traits\ModelTrait;
use app\models\News;
use app\modules\admin\models\forms\NewsForm;
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
                'modelClass' => News::class,
                'operations' => [
                    'delete' => [],
                    'set-publish' => ['status' => News::STATUS_ACTIVE],
                    'set-unpublish' => ['status' => News::STATUS_BLOCKED]
                ]
            ],
            'set-publish' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => News::class,
                'attributes' => ['status' => News::STATUS_ACTIVE],
            ],
            'set-unpublish' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelClass' => News::class,
                'attributes' => ['status' => News::STATUS_BLOCKED],
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelClass' => News::class,
            ],
            'text-upload' => [
                'class' => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => News::class,
                'attribute' => 'text',
                'inputName' => 'file',
                'resultFieldPath' => 'filelink',
            ],
            'preview-upload' => [
                'class' => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => News::class,
                'attribute' => 'preview',
                'inputName' => 'file',
            ],
            'gallery-upload' => [
                'class' => 'rkit\filemanager\actions\UploadAction',
                'modelClass' => News::class,
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
        $model = new NewsForm();

        if ($id) {
            $model->setModel($this->findModel(new News, $id));
        }

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $data['NewsForm'] = $data['NewsForm'] + $data['News'];

            if ($model->load($data) && $model->validate()) {
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
}
