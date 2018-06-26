<?php

namespace app\modules\admin\actions;

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

    public function run(string $id)
    {
        $model = $this->modelClass::findOne($id);

        $model->setAttributes($this->attributes, false);
        return $this->controller->asJson($model->save(false));
    }
}
