<?php

namespace app\modules\admin\controllers;

use Yii;
use app\traits\ControllerTrait;
use app\modules\admin\models\forms\SettingsForm;

class SettingsController extends \yii\web\Controller
{
    use ControllerTrait;

    public function actionIndex()
    {
        $model = new SettingsForm();
 
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = 'json';

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->settings->load($model->getAttributes());

                Yii::$app->session->setFlash('success', Yii::t('app.msg', 'Saved successfully'));
                return $this->refresh();
            }
            return $this->asJsonModelErrors($model);
        }

        $model->setAttributes(Yii::$app->settings->all());

        return $this->render('index', ['model' => $model]);
    }
}
