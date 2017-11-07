<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;

class IndexController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /** @see commands/MaintenanceController **/
    public function actionMaintenance()
    {
        if (!Yii::$app->catchAll) {
            throw new NotFoundHttpException(Yii::t('app.msg', 'Page not found'));
        }

        $this->layout = 'maintenance';
        return $this->render('maintenance');
    }
}
