<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\traits\ModelTrait;
use app\models\AuthItem;
use app\modules\admin\models\forms\AuthItemForm;
use app\modules\admin\models\search\AuthItemSearch;

class RolesController extends \yii\web\Controller
{
    use ModelTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
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
                'modelClass' => 'app\models\AuthItem',
                'operations' => [
                    'delete' => [],
                ]
            ],
            'delete' => [
                'class' => 'app\modules\admin\controllers\common\DeleteAction',
                'modelClass' => 'app\models\AuthItem',
            ],
        ];
    }

    public function actionIndex()
    {
        $authItemSearch = new AuthItemSearch();
        $dataProvider = $authItemSearch->search(Yii::$app->request->get());

        return $this->render('index', [
            'authItemSearch' => $authItemSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($name = null)
    {
        $model = new AuthItemForm();

        if ($name) {
            $model->setModel($this->findModel(new AuthItem, $name));
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();

                Yii::$app->session->setFlash('success', Yii::t('app.msg', 'Saved successfully'));
                return $this->asJson(['redirect' => Url::toRoute(['edit', 'name' => $model->name])]);
            }
            return $this->asJson($this->collectErrors($model));
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }
}
