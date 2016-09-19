<?php

namespace app\helpers;

use yii\helpers\Html;

class Page
{
    /**
     * Make page title
     *
     * @param string $title
     * @param string $appendToEnd
     * @return string
     */
    public static function title($title = '', $appendToEnd = '')
    {
        $title = $title ? self::clear($title) . ' / ' : '';
        return Html::tag('title', $title . $appendToEnd);
    }

    private static function clear($text)
    {
        $text = str_replace('"', '“', $text);
        return Html::encode(html_entity_decode(strip_tags($text)));
    }
}
