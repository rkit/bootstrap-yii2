<?php

namespace app\helpers;

use Yii;
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
     * Can use for meta tags
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
     * Make page title
     *
     * @param string $title
     * @param string $appendToEnd
     * @return string
     */
    public static function makePageTitle($title = '', $appendToEnd = '')
    {
        $title = $title ? self::clearText($title) . ' / ' : '';
        return $title . $appendToEnd;
    }

   /**
    * Collect model errors
    *
    * @param Model $model the model to be validated
    * @param mixed $attributes list of attributes that should be validated.
    * @return array the error message array indexed by the attribute IDs.
    */
    public static function collectModelErrors($model)
    {
        $result = [];
        /* @var $model Model */
        $models = [$model];
        foreach ($models as $model) {
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[\yii\helpers\Html::getInputId($model, $attribute)] = $errors;
            }
        }
        return $result;
    }
}
