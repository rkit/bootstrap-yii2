<?php

namespace app\modules\admin\actions;

use yii\base\Action;

class DeleteAction extends Action
{
    /**
     * @var string $modelClass
     */
    public $modelClass;

    public function run(string $id, bool $reload = false)
    {
        $this->modelClass::findOne($id)->delete();

        if ($reload) {
            return $this->controller->redirect(\yii\helpers\Url::to(['index']));
        }

        return $this->controller->asJson(true);
    }
}
