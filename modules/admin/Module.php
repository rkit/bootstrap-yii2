<?php

namespace app\modules\admin;

use Yii;
use yii\web\ForbiddenHttpException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $defaultRoute = 'index/index';
    public $layout = 'admin';

    public function init()
    {
        parent::init();

        Yii::$app->user->loginUrl = ['admin/index/login'];

        \Yii::$container->set('yii\widgets\LinkPager', [
            'maxButtonCount' => 5,
            'nextPageLabel'  => '&rarr;',
            'prevPageLabel'  => '&larr;',
            'firstPageLabel' => '&lArr;',
            'lastPageLabel'  => '&rArr;',
        ]);
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            return $this->checkAccess($action);
        }
        return false;
    }

    public function checkAccess($action)
    {
        if ($action->controller->id == 'index') {
            return true;
        }

        if (!\Yii::$app->user->can('AdminModule') ||
            !\Yii::$app->user->can($this->getCurrentPermissionName($action))
        ) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access Denied'));
        }

        return true;
    }

    private function getCurrentPermissionName($action)
    {
        return 'ACTION_Admin' . ucfirst($action->controller->id);
    }
}
