<?php

namespace app\helpers;

use yii\helpers\Html;

class Util
{
    /**
     * Convert TZ.
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
     * Clear text.
     * For meta tags & title page.
     *
     * @param string $text
     * @return string
     */
    public static function clearText($text)
    {
        $text = str_replace('"', '“', $text);
        return Html::encode(html_entity_decode(strip_tags($text)));
    }
}
