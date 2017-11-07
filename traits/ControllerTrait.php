<?php

namespace app\traits;

use Yii;
use yii\helpers\Html;
use yii\web\{Response, NotFoundHttpException};
use yii\db\ActiveRecord;

trait ControllerTrait
{
    /**
     * Find the model based on its primary key value or WHERE condition.
     * If the model is not found, then 404 HTTP exception will be thrown
     *
     * @param string $model Class name
     * @param mixed $condition primary key or WHERE condition
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModel(string $model, $condition): ActiveRecord
    {
        $model = $model::findOne($condition);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }

        return $model;
    }

    /**
     * Find the model based on prepared ActiveQuery
     * If the model is not found, then 404 HTTP exception will be thrown
     *
     * @param ActiveQuery $query
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModelByQuery($query): ActiveRecord
    {
        $model = $query->one();

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }

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
