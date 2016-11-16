<?php

namespace app\modules\admin\controllers\common;

use Yii;
use yii\base\Action;
use yii\web\Response;
use app\traits\ModelTrait;

class UpdateAttributesAction extends Action
{
    use ModelTrait;

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
        $model = $this->findModel($model, $id);

        $model->updateAttributes($this->attributes);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }
}
