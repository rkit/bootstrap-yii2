<?php

namespace app\modules\admin\controllers\common;

use yii\base\Action;

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

        return $this->controller->response(true);
    }
}
