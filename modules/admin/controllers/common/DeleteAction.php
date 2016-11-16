<?php

namespace app\modules\admin\controllers\common;

use Yii;
use yii\base\Action;
use yii\web\Response;

class DeleteAction extends Action
{
    /**
     * @var string $modelClass
     */
    public $modelClass;

    public function run($id, $reload = false)
    {
        $model = new $this->modelClass;
        $this->controller->loadModel($model, $id)->delete();

        if ($reload) {
            return $this->controller->redirect(\yii\helpers\Url::to(['index']));
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }
}
