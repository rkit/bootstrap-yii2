<?php

namespace app\traits;

use app\helpers\Http;

trait ModelTrait
{
    /**
     * Find the model based on its primary key value or WHERE condition.
     * If the model is not found or access denied, a 404 HTTP exception will be thrown.
     *
     * @param ActiveRecord $model
     * @param int|array $id primary key or WHERE condition
     * @param callable $checkAccess
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModel($model, $id, $checkAccess = null)
    {
        $model = $model::findOne($id);

        if ($model === null || ($checkAccess !== null && !$model->$checkAccess())) {
            Http::exception(404);
        } // @codeCoverageIgnore

        return $model;
    }
}
