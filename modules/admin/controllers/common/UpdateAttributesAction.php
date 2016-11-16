<?php

namespace app\modules\admin\controllers\common;

use Yii;
use yii\base\Action;
use yii\web\Response;

class UpdateAttributesAction extends Action
{
    /**
     * @var string $modelClass
     */
    public $modelClass;
    /**
     * @var array $attributes
     */
    public $attributes;

    public function run($id)
    {
        $model = new $this->modelClass;
        $model = $this->controller->loadModel($model, $id);

        $model->updateAttributes($this->attributes);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }
}
