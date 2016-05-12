<?php

namespace app\modules\admin\controllers\common;

use Yii;
use yii\base\Action;

class OperationsAction extends Action
{
    /**
     * @var string $modelName
     */
    public $modelName;

    public function run()
    {
        $model = new $this->modelName();

        if (($ids = Yii::$app->request->post('selection')) !== null) {
            $models = $model::findAll($ids);
            $operation = Yii::$app->request->post('operation');

            foreach ($models as $model) {
                switch ($operation) {
                    case 'delete':
                        $model->delete();
                        break;
                    case 'activate':
                        $model->updateAttributes(['status' => $model::STATUS_ACTIVE]);
                        break;
                    case 'block':
                        $model->updateAttributes(['status' => $model::STATUS_BLOCKED]);
                        break;
                }
            }
        }

        return $this->controller->response(true);
    }
}
