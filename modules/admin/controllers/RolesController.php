<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\traits\ModelTrait;
use app\models\entity\AuthItem;
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
                    'batch' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'batch' => [
                'class' => 'app\modules\admin\actions\BatchAction',
                'modelClass' => 'app\models\entity\AuthItem',
                'actions' => [
                    'delete' => [],
                ]
            ],
            'delete' => [
                'class' => 'app\modules\admin\actions\DeleteAction',
                'modelClass' => 'app\models\entity\AuthItem',
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
            Yii::$app->response->format = 'json';

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();

                Yii::$app->session->setFlash('success', Yii::t('app.msg', 'Saved successfully'));
                return $this->redirect(['edit', 'name' => $model->name]);
            }
            return $this->asJsonModelErrors($model);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }
}
