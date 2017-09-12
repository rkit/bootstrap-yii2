<?php

namespace app\modules\admin\actions;

use Yii;
use yii\base\Action;

class BatchAction extends Action
{
    /**
     * @var string $modelClass
     */
    public $modelClass;
    /**
     * @var array $actions
     */
    public $actions;

    public function run()
    {
        $model = new $this->modelClass;

        if (($ids = Yii::$app->request->post('selection')) !== null) {
            $models = $model::findAll($ids);
            $operation = Yii::$app->request->post('operation');

            if (isset($this->actions[$operation])) {
                $attrubutes = $this->actions[$operation];
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
