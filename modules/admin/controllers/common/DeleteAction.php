<?php

namespace app\modules\admin\controllers\common;

use yii\base\Action;

class DeleteAction extends Action
{
    /**
     * @var string $modelName
     */
    public $modelName;

    public function run($id, $reload = false)
    {
        $model = new $this->modelName();

        $this->controller->loadModel($model, $id)->delete();

        if ($reload) {
            return $this->controller->redirect(\yii\helpers\Url::to(['index']));
        }
        return $this->controller->response(true);
    }
}
