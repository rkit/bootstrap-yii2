<?php

namespace app\modules\admin\controllers\common;

use yii\base\Action;

class BlockAction extends Action
{
    /**
     * @var string $modelName
     */
    public $modelName;
    
    public function run($id)
    {
        $model = new $this->modelName();
        
        $model = $this->controller->loadModel($model, $id);
        $model->updateAttributes(['status' => $model::STATUS_BLOCKED]);

        return $this->controller->response(true);
    }
}
