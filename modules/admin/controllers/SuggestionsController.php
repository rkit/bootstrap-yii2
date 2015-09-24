<?php

namespace app\modules\admin\controllers;

use app\components\BaseController;
use yii\filters\VerbFilter;
use Yii;

class SuggestionsController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['post'],
                ],
            ],
        ];
    }

    public function actionTags()
    {
        if (($term = Yii::$app->request->post('term')) !== null) {
            $data = \app\models\Tag::find()
                ->like($term, 'title')
                ->asArray()
                ->limit(10)
                ->all();
            return $this->response($this->prepare($data));
        }

        return $this->response([]);
    }

    public function actionCountries()
    {
        if (($term = Yii::$app->request->post('term')) !== null) {
            $data = \app\models\Country::find()
                ->like($term, 'title')
                ->asArray()
                ->limit(10)
                ->all();
            return $this->response($this->prepare($data, 'title', 'country_id'));
        }

        return $this->response([]);
    }

    public function actionRegions()
    {
        if (($term = Yii::$app->request->post('term')) !== null) {
            $data = \app\models\Region::find()
                ->joinWith('country')
                ->like($term, 'region.title')
                ->asArray()
                ->limit(10)
                ->all();

            foreach ($data as &$item) {
                $item['title'] = $item['country']['title'] . ', ' . $item['title'];
            }

            return $this->response($this->prepare($data, 'title', 'region_id'));
        }

        return $this->response([]);
    }

    private function prepare(
        $list,
        $valueField = 'title',
        $idField = 'title',
        $resultValue = 'text',
        $resultId = 'id'
    ) {
        $result = [];
        foreach ($list as $item) {
            $result[] = [
                $resultValue => $item[$valueField],
                $resultId    => $item[$idField]
            ];
        }

        return $result;
    }
}
