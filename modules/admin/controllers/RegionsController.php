<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\helpers\Util;
use app\components\BaseController;
use app\models\Region;
use app\modules\admin\models\search\RegionSearch;

class RegionsController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'operations' => ['post'],
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
                'modelName' => 'app\models\Region',
                'operations' => [
                    'delete' => [],
                ]
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelName' => 'app\models\Region',
            ],
        ];
    }

    public function actionIndex()
    {
        $regionSearch = new RegionSearch();
        $dataProvider = $regionSearch->search(Yii::$app->request->get());

        return $this->render('index', [
            'regionSearch' => $regionSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = new Region();

        if ($id) {
            $model = $this->loadModel($model, $id);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app.messages', 'Saved successfully'));
                $urlToModel = Url::toRoute(['edit', 'id' => $model->region_id]);
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

    public function actionAutocomplete()
    {
        $result = [];
        if (($term = Yii::$app->request->post('term')) !== null) {
            $data = Region::find()
                ->joinWith('country')
                ->like('region.title', $term)
                ->asArray()
                ->limit(10)
                ->all();

            foreach ($data as $item) {
                $result[] = [
                    'text' => $item['title'],
                    'id' => $item['region_id']
                ];
            }
        }
        return $this->response($result);
    }
}
