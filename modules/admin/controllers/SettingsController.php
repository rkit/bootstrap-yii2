<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\BaseController;
use app\modules\admin\models\forms\Settings;

class SettingsController extends BaseController
{
    public function actionIndex()
    {
        $model = new Settings();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->settings->load($model->getAttributes());
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->refresh();
            } else {
                if (Yii::$app->request->isAjax) {
                    return $this->response(\app\helpers\Util::getValidationErrors($model));
                }
            }
        }

        $model->setAttributes(Yii::$app->settings->all());

        return $this->render('index', ['model' => $model]);
    }
}
