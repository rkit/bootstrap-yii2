<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\helpers\Model;
use app\components\BaseController;
use app\models\Country;
use app\modules\admin\models\search\CountrySearch;

class CountriesController extends BaseController
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
                'modelName' => 'app\models\Country',
                'operations' => [
                    'delete' => [],
                ]
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelName' => 'app\models\Country',
            ],
        ];
    }

    public function actionIndex()
    {
        $countrySearch = new CountrySearch();
        $dataProvider = $countrySearch->search(Yii::$app->request->get());

        return $this->render('index', [
            'countrySearch' => $countrySearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($id = null)
    {
        $model = new Country();

        if ($id) {
            $model = $this->loadModel($model, $id);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app.messages', 'Saved successfully'));
                $urlToModel = Url::toRoute(['edit', 'id' => $model->country_id]);
                if (Yii::$app->request->isAjax) {
                    return $this->response(['redirect' => $urlToModel]);
                }
                return $this->redirect($urlToModel);
            }
            if (Yii::$app->request->isAjax) {
                return $this->response(Model::collectErrors($model));
            }
        }

        return $this->render('edit', ['model' => $model]);
    }

    public function actionAutocomplete()
    {
        $result = [];
        if (($term = Yii::$app->request->post('term')) !== null) {
            $data = Country::find()
                ->like('title', $term)
                ->asArray()
                ->limit(10)
                ->all();

            foreach ($data as $item) {
                $result[] = [
                    'text' => $item['title'],
                    'id' => $item['country_id']
                ];
            }
        }
        return $this->response($result);
    }
}
