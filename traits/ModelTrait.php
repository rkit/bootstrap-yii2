<?php

namespace app\traits;

use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\web\Response;

trait ModelTrait
{
    /**
     * Find the model based on its primary key value or WHERE condition.
     * If the model is not found or access denied, a 404 HTTP exception will be thrown.
     *
     * @param ActiveRecord $model
     * @param mixed $id primary key or WHERE condition
     * @param string $checkAccess
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModel(ActiveRecord $model, $id, string $checkAccess = null): ActiveRecord
    {
        $model = $model::findOne($id);

        if ($model === null || ($checkAccess !== null && !$model->$checkAccess())) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        } // @codeCoverageIgnore

        return $model;
    }

    /**
     * Returns an error messages as an array indexed by the ID.
     * This is a helper method that simplifies the way of writing AJAX validation code
     *
     * @param yii\base\Model $model
     * @return yii\web\Response
     */
    public function asJsonModelErrors(\yii\base\Model $model): Response
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->statusCode = 422;

        $result = [];
        foreach ($model->getErrors() as $attribute => $errors) {
            $result[Html::getInputId($model, $attribute)] = $errors;
        }

        $response->data = $result;
        return $response;
    }
}
