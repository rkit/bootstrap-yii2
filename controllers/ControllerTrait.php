<?php

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use yii\web\Response;

trait ControllerTrait
{
    /**
     * Returns an error messages as an array indexed by the ID.
     * This is a helper method that simplifies the way of writing AJAX validation code.
     *
     * @param yii\base\Model|yii\base\Model[] $model
     * @return yii\web\Response
     */
    public function asJsonModelErrors($model): Response
    {
        $models = is_array($model) ? $model : [$model];

        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->statusCode = 422;

        $result = [];
        foreach ($models as $model) {
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }    
        }     

        $response->data = $result;
        return $response;
    }
}
