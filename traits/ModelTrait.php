<?php

namespace app\traits;

use yii\helpers\Html;
use yii\web\NotFoundHttpException;

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
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        } // @codeCoverageIgnore

        return $model;
    }

    /**
     * Collect model errors
     *
     * @param Model $model the model to be validated
     * @return array the error message array indexed by the attribute IDs.
     */
     public static function collectErrors($model)
     {
         $result = [];
         /* @var $model Model */
         $models = [$model];
         foreach ($models as $model) {
             foreach ($model->getErrors() as $attribute => $errors) {
                 $result[Html::getInputId($model, $attribute)] = $errors;
             }
         }
         return $result;
     }
}
