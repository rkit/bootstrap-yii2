<?php

namespace app\modules\admin\actions;

use Yii;
use yii\base\Action;
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

    public function run(string $id)
    {
        $model = new $this->modelClass;
        $model = $this->findModel($model, $id);

        $model->updateAttributes($this->attributes);

        return $this->controller->asJson(true);
    }
}
