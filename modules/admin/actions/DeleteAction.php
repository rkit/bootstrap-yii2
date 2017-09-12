<?php

namespace app\modules\admin\actions;

use Yii;
use yii\base\Action;
use app\traits\ModelTrait;

class DeleteAction extends Action
{
    use ModelTrait;

    /**
     * @var string $modelClass
     */
    public $modelClass;

    public function run(string $id, bool $reload = false)
    {
        $model = new $this->modelClass;
        $this->findModel($model, $id)->delete();

        if ($reload) {
            return $this->controller->redirect(\yii\helpers\Url::to(['index']));
        }

        return $this->controller->asJson(true);
    }
}
