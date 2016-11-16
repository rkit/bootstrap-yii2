<?php

namespace app\components;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\helpers\Http;

/**
 * Global base controller
 */
class BaseController extends Controller
{
    /**
     * Load the model based on its primary key value or WHERE condition.
     * If the model is not found or access denied, a 404 HTTP exception will be thrown.
     *
     * @param ActiveRecord $model
     * @param int|array $id primary key or WHERE condition
     * @param callable $checkAccess
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function loadModel($model, $id, $checkAccess = null)
    {
        $model = $model::findOne($id);

        if ($model === null || ($checkAccess !== null && !$model->$checkAccess())) {
            Http::exception(404);
        } // @codeCoverageIgnore

        return $model;
    }
}
