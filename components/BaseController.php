<?php

namespace app\components;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\helpers\Http;

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
     * Load the model based on its primary key value or WHERE condition.
     * If the model is not found or access denied, a 404 HTTP exception will be thrown.
     *
     * @param ActiveRecord $model
     * @param int|array $id primary key or WHERE condition
     * @param callable $checkAccess
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function loadModel($model, $id, $checkAccess = null)
    {
        $model = $model::findOne($id);

        if ($model === null || ($checkAccess !== null && !$model->$checkAccess())) {
            Http::exception(404);
        } // @codeCoverageIgnore

        return $model;
    }
}
