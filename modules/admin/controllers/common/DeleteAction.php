<?php

namespace app\modules\admin\controllers\common;

use Yii;
use yii\base\Action;
use yii\web\Response;
use app\traits\ModelTrait;

class DeleteAction extends Action
{
    use ModelTrait;

    /**
     * @var string $modelClass
     */
    public $modelClass;

    public function run($id, $reload = false)
    {
        $model = new $this->modelClass;
        $this->findModel($model, $id)->delete();

        if ($reload) {
            return $this->controller->redirect(\yii\helpers\Url::to(['index']));
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }
}
