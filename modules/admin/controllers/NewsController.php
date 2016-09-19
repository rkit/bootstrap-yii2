<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\helpers\Model;
use app\components\BaseController;
use app\models\News;
use app\models\NewsType;
use app\modules\admin\models\search\NewsSearch;

class NewsController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'publish' => ['post'],
                    'unpublish' => ['post'],
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
                'modelName' => 'app\models\News',
                'operations' => [
                    'delete' => [],
                    'set-publish' => ['status' => News::STATUS_ACTIVE],
                    'set-unpublish' => ['status' => News::STATUS_BLOCKED]
                ]
            ],
            'set-publish' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelName' => 'app\models\News',
                'attributes' => ['status' => News::STATUS_ACTIVE],
            ],
            'set-unpublish' => [
                'class' => 'app\modules\admin\controllers\common\UpdateAttributesAction',
                'modelName' => 'app\models\News',
                'attributes' => ['status' => News::STATUS_BLOCKED],
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelName' => 'app\models\News',
            ],
            'text-upload' => [
                'class' => 'rkit\filemanager\actions\UploadAction',
                'modelName' => 'app\models\News',
                'attribute' => 'text',
                'inputName' => 'file',
                'resultFieldPath' => 'filelink',
                'temporary' => false,
            ],
            'preview-upload' => [
                'class'     => 'rkit\filemanager\actions\UploadAction',
                'modelName' => 'app\models\News',
                'attribute' => 'preview',
                'inputName' => 'file',
            ],
            'gallery-upload' => [
                'class'     => 'rkit\filemanager\actions\UploadAction',
                'modelName' => 'app\models\News',
                'attribute' => 'gallery',
                'inputName' => 'file',
                'multiple'  => true,
                'template'  => Yii::getAlias('@app/modules/admin/views/shared/files/gallery/item.php'),
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
            'types' => NewsType::find()->orderBy('title')->asArray()->all()
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = new News();

        if ($id) {
            $model = $this->loadModel($model, $id);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app.messages', 'Saved successfully'));
                $urlToModel = Url::toRoute(['edit', 'id' => $model->id]);
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
            'types' => NewsType::find()->orderBy('title')->asArray()->all()
        ]);
    }
}
