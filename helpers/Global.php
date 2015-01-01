<?php 

use yii\helpers\Html;

/**
 * Escapes the given string using Html::encode().
 *
 * @param $text
 * @return string
 */
function e($text)
{
    return Html::encode($text);
}

/**
 * Beautiful dump.
 *
 * @param mixed $var
 * @param int $depth
 * @param bool $highlight
 */
function dump($var, $depth = 10, $highlight = true)
{
    echo yii\helpers\VarDumper::dump($var, $depth, $highlight);
}

/**
 * Return current user.
 *
 * @return yii\web\User
 */
function user()
{
    return Yii::$app->user;
}
