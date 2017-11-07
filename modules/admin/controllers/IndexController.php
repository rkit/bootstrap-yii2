<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use app\modules\admin\models\forms\LoginForm;

class IndexController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index'],
                        'roles'   => ['@', 'AdminModule'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['login'],
                        'roles'   => ['?'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['logout'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['error'],
                        'roles'   => ['*'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->can('AdminModule')) {
            throw new ForbiddenHttpException(Yii::t('app.msg', 'Access Denied'));
        }

        return $this->render('index');
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
