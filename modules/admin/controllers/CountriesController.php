<?php

namespace app\modules\admin\controllers;

use app\components\BaseController;
use app\models\Country;
use app\modules\admin\models\search\CountrySearch;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use Yii;

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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->response(['redirect' => Url::toRoute(['edit', 'id' => $model->country_id])]);   
            } else {
                return $this->response(['errors' => $model->getErrors(), 'prefix' => 'country-']);            
            }
        }

        return $this->render('edit', ['model' => $model]);
    }
}
