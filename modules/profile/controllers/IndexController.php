<?php

namespace app\modules\profile\controllers;

use Yii;

class IndexController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/']);
    }
}
