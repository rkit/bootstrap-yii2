<?php

namespace app\helpers;

use yii\helpers\Html;

class PageTitle
{
    public static function process($text)
    {
        $text = str_replace('"', '“', $text);
        $text = Html::encode($text);

        return $text;
    }
}
