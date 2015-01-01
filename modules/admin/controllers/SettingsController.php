<?php

namespace app\modules\admin\controllers;

use app\components\BaseController;

use yii\helpers\ArrayHelper;
use Yii;

class SettingsController extends BaseController
{
    public function actionIndex()
    {        
        $model = new \app\models\Settings();
        
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                foreach ($model->getAttributes() as $key => $value) {
                    Yii::$app->settings->set($key, $value);
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->refresh(); 
            } else {
                return $this->response(['errors' => $model->getErrors(), 'prefix' => 'settings-']);            
            }
        }
        
        $settings = ArrayHelper::map(Yii::$app->settings->load(true), 'key', 'value');
        $model->setAttributes($settings);
        
        return $this->render('index', ['model' => $model]);
    }
}
