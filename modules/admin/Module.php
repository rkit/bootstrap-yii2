<?php

namespace app\modules\admin;

use Yii;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        Yii::$app->user->loginUrl = ['admin/index/login'];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'adminFilter' =>  [
                'class' => 'app\modules\admin\filters\AdminFilter',
            ],
        ];
    }
}
