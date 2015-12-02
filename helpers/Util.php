<?php

namespace app\helpers;

use yii\helpers\Html;

class Util
{
    /**
     * Convert TZ
     *
     * @param string $date
     * @param string $fromTimeZone
     * @param string $toTimeZone
     * @param string $format
     * @return string
     */
    public static function convertTz($date, $fromTimeZone, $toTimeZone, $format = 'Y-m-d H:i:s')
    {
        $date = new \DateTime($date, new \DateTimeZone($fromTimeZone));
        $date->setTimeZone(new \DateTimeZone($toTimeZone));

        return $date->format($format);
    }

    /**
     * Clear text
     * For meta tags & title page
     *
     * @param string $text
     * @return string
     */
    public static function clearText($text)
    {
        $text = str_replace('"', '“', $text);
        return Html::encode(html_entity_decode(strip_tags($text)));
    }

   /**
    * Get validation errors
    *
    * @param Model $model the model to be validated
    * @param mixed $attributes list of attributes that should be validated.
    * @return array the error message array indexed by the attribute IDs.
    */
    public static function getValidationErrors($model, $attributes = null)
    {
        $result = [];
        if ($attributes instanceof Model) {
            // validating multiple models
            $models = func_get_args();
            $attributes = null;
        } else {
            $models = [$model];
        }
        /* @var $model Model */
        foreach ($models as $model) {
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[\yii\helpers\Html::getInputId($model, $attribute)] = $errors;
            }
        }
        return $result;
    }
}
