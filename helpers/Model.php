<?php

namespace app\helpers;

use yii\helpers\Html;

class Model
{
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
