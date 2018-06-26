<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\controllers\ControllerTrait;
use app\models\entity\AuthItem;
use app\modules\admin\models\forms\AuthItemForm;
use app\modules\admin\models\search\AuthItemSearch;

class RolesController extends \yii\web\Controller
{
    use ControllerTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
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
        $search = new AuthItemSearch();
        $provider = $search->search(Yii::$app->request->get());

        return $this->render('index', [
            'search' => $search,
            'provider' => $provider,
        ]);
    }

    public function actionEdit($name = null)
    {
        $model = new AuthItemForm();

        if ($name) {
            $model->setModel($this->findModel($name));
        }

        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = 'json';

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();

                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->redirect(['edit', 'name' => $model->name]);
            }
            return $this->asJsonModelErrors($model);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Find the model.
     * If the model is not found, then 404 HTTP exception will be thrown.
     *
     * @param string $name
     * @return Model
     * @throws NotFoundHttpException
     */
    private function findModel($name): yii\base\Model
    {
        $model = AuthItem::findOne($name);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }

        return $model;
    }
}
