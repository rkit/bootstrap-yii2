<?php

namespace app\modules\admin\controllers\common;

use Yii;
use yii\base\Action;

class OperationsAction extends Action
{
    /**
     * @var string $modelClass
     */
    public $modelClass;
    /**
     * @var array $operations
     */
    public $operations;

    public function run()
    {
        $model = new $this->modelClass;

        if (($ids = Yii::$app->request->post('selection')) !== null) {
            $models = $model::findAll($ids);
            $operation = Yii::$app->request->post('operation');

            if (isset($this->operations[$operation])) {
                $attrubutes = $this->operations[$operation];
                foreach ($models as $model) {
                    switch ($operation) {
                        case 'delete':
                            $model->delete();
                            break;
                        default:
                            $model->updateAttributes($attrubutes);
                            break;
                    }
                }
            }
        }

        return $this->controller->asJson(true);
    }
}
