<?php

namespace app\modules\admin\controllers;

use Yii;
use app\components\BaseController;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\forms\Settings;

class SettingsController extends BaseController
{
    public function actionIndex()
    {
        $model = new Settings();
        $settings = Yii::$app->settings;

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $rows = [];
                foreach ($model->getAttributes() as $key => $value) {
                    $rows[] = ['key' => $key, 'value' => $value];
                }

                Yii::$app->db->createCommand()->truncateTable($settings->tableName)->execute();
                Yii::$app->db->createCommand()->batchInsert($settings->tableName, ['key', 'value'], $rows)->execute();

                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved successfully'));
                return $this->refresh();
            } else {
                return $this->response(['errors' => $model->getErrors(), 'prefix' => 'settings-']);
            }
        }

        $settings = ArrayHelper::map($settings->load(true), 'key', 'value');
        $model->setAttributes($settings);

        return $this->render('index', ['model' => $model]);
    }
}
