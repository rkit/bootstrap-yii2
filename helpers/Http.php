<?php

namespace app\helpers;

use Yii;

class Http
{
    /**
     * Call HTTP Exception
     *
     * @param int $code HTTP Status
     * @param string $msg Message
     * @throws HttpException when invoked
     */
    public static function exception($code, $msg = null)
    {
        switch ($code) {
            case 400:
                $msg = $msg ? $msg : Yii::t('app.messages', 'Bad request');
                throw new \yii\web\BadRequestHttpException($msg);
            case 401:
                $msg = $msg ? $msg : Yii::t('app.messages', 'Access is unauthorized');
                throw new \yii\web\UnauthorizedHttpException($msg);
            case 403:
                $msg = $msg ? $msg : Yii::t('app.messages', 'Access Denied');
                throw new \yii\web\ForbiddenHttpException($msg);
            case 404:
            default:
                $msg = $msg ? $msg : Yii::t('app.messages', 'Page not found');
                throw new \yii\web\NotFoundHttpException($msg);
        } // @codeCoverageIgnore
    }
}
