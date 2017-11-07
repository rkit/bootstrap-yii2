<?php

namespace app\modules\admin\actions;

use Yii;
use yii\base\Action;
use app\traits\ControllerTrait;

class UpdateAttributesAction extends Action
{
    use ControllerTrait;

    /**
     * @var string $modelClass
     */
    public $modelClass;
    /**
     * @var array $attributes
     */
    public $attributes;

    public function run(string $id)
    {
        $model = $this->findModel($this->modelClass, $id);

        $model->setAttributes($this->attributes, false);
        return $this->controller->asJson($model->save(false));
    }
}
