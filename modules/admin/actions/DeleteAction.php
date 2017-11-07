<?php

namespace app\modules\admin\actions;

use yii\base\Action;
use app\traits\ControllerTrait;

class DeleteAction extends Action
{
    use ControllerTrait;

    /**
     * @var string $modelClass
     */
    public $modelClass;

    public function run(string $id, bool $reload = false)
    {
        $this->findModel($this->modelClass, $id)->delete();

        if ($reload) {
            return $this->controller->redirect(\yii\helpers\Url::to(['index']));
        }

        return $this->controller->asJson(true);
    }
}
