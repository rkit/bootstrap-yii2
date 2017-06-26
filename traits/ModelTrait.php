<?php

namespace app\traits;

use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;

trait ModelTrait
{
    /**
     * Find the model based on its primary key value or WHERE condition.
     * If the model is not found or access denied, a 404 HTTP exception will be thrown.
     *
     * @param ActiveRecord $model
     * @param string $id primary key or WHERE condition
     * @param string $checkAccess
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModel(ActiveRecord $model, string $id, string $checkAccess = null): ActiveRecord
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
    * @param yii\base\Model $model the model to be validated
    * @return array the error message array indexed by the attribute IDs.
    */
    public static function collectErrors(yii\base\Model $model): array
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
