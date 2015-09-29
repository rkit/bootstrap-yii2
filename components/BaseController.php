<?php

namespace app\components;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

/**
 * Global base controller
 */
class BaseController extends Controller
{
    /**
     * @var string Path to js bundle
     */
    public $jsBundle = 'front.js';
    /**
     * @var string Path to css bundle
     */
    public $cssBundle = 'front.css';

    /**
     * @return string Return CSS bundle
     */
    public function getCssBundle()
    {
        return '/assets/' . ArrayHelper::getValue(Yii::$app->params['assets'], $this->cssBundle);
    }

    /**
     * @return string Return JS bundle
     */
    public function getJsBundle()
    {
        return '/assets/' . ArrayHelper::getValue(Yii::$app->params['assets'], $this->jsBundle);
    }

    /**
     * Load the model based on its primary key value
     * If the model is not found or access denied, a 404 HTTP exception will be thrown
     *
     * @param \yii\db\ActiveRecord $model
     * @param int $id
     * @param bool $ownerCheck
     * @return ActiveRecord|void
     */
    public function loadModel($model, $id, $ownerCheck = false)
    {
        $model = $model::findOne($id);

        if ($model === null || ($ownerCheck && !$model->isOwner())) {
            return $this->pageNotFound();
        }

        return $model;
    }

    /**
     * Show alert message
     *
     * @param string $type success|error
     * @param string $message
     * @param string $url If null go Home
     */
    public function alert($type, $message, $url = null)
    {
        Yii::$app->getSession()->setFlash($type, $message);
        return $url ? $this->redirect($url) : $this->goHome();
    }

    /**
     * JSON Response
     *
     * @param mixed $data
     */
    public function response($data)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    /**
     * Echo and exit
     *
     * @param mixed $data
     */
    public function end($data)
    {
        echo $data;
        Yii::$app->end();
    }

    /**
     * Triggers a 404 (Page Not Found) error
     *
     * @param string $msg
     * @throws CHttpException when invoked
     */
    public function pageNotFound($msg = null)
    {
        throw new \yii\web\HttpException(404, $msg ? $msg : Yii::t('app', 'Page not found'));
    }

    /**
     * Triggers a 403 (Access Denied) error
     *
     * @param string $msg
     * @throws CHttpException when invoked
     */
    public function accessDenied($msg = null)
    {
        throw new \yii\web\HttpException(403, $msg ? $msg : Yii::t('app', 'Access Denied'));
    }

    /**
     * Triggers a 400 (Bad Request) error
     *
     * @param string $msg
     * @throws CHttpException when invoked
     */
    public function badRequest($msg = null)
    {
        throw new \yii\web\HttpException(400, $msg ? $msg : Yii::t('app', 'Bad request'));
    }
}
