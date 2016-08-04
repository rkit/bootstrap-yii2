<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\helpers\Util;
use app\components\BaseController;
use app\models\NewsType;
use app\modules\admin\models\search\NewsTypeSearch;

class NewsTypesController extends BaseController
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
                'modelName' => 'app\models\NewsType',
                'operations' => [
                    'delete' => [],
                ]
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelName' => 'app\models\NewsType',
            ],
        ];
    }

    public function actionIndex()
    {
        $newsTypeSearch = new NewsTypeSearch();
        $dataProvider = $newsTypeSearch->search(Yii::$app->request->get());

        return $this->render('index', [
            'newsTypeSearch' => $newsTypeSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = new NewsType();

        if ($id) {
            $model = $this->loadModel($model, $id);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app.messages', 'Saved successfully'));
                $urlToModel = Url::toRoute(['edit', 'id' => $model->id]);
                if (Yii::$app->request->isAjax) {
                    return $this->response(['redirect' => $urlToModel]);
                } else {
                    return $this->redirect($urlToModel);
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    return $this->response(Util::collectModelErrors($model));
                }
            }

        }

        return $this->render('edit', ['model' => $model]);
    }
}
