<?php

namespace app\services;

use Yii;

class Tokenizer
{
    /**
     * Generate a new token
     * @return string
     */
    public function generate(): string
    {
        return Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Validate token
     * @param string $token
     * @return bool
     */
    public function validate(string $token = null): bool
    {
        if (empty($token)) {
            return false;
        }

        $expire = Yii::$app->params['user.tokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);

        return $timestamp + $expire >= time();
    }
}
