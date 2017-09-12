<?php

namespace app\modules\admin;

use Yii;
use yii\web\ForbiddenHttpException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $defaultRoute = 'index/index';
    public $layout = 'admin';
    public $permissions = [];

    public function init()
    {
        parent::init();

        if (Yii::$app->user->isGuest === false) {
            $this->permissions = $this->getUserPermissions();
        }

        Yii::$app->user->loginUrl = ['admin/index/login'];
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
        if ($action->controller->id === 'index') {
            return true;
        }

        if (!\Yii::$app->user->can('AdminModule') ||
            !\Yii::$app->user->can($this->getCurrentPermissionName($action))
        ) {
            throw new ForbiddenHttpException(Yii::t('app.msg', 'Access Denied'));
        }

        return true;
    }

    private function getCurrentPermissionName($action)
    {
        return 'ACTION_Admin' . ucfirst($action->controller->id);
    }

    private function getUserPermissions()
    {
        $authManager = Yii::$app->authManager;

        if (Yii::$app->user->identity->isSuperUser() === false) {
            return $authManager->getPermissionsByRole(Yii::$app->user->identity->role);
        }

        return Yii::$app->cache->getOrSet(
            'rbac-permissions',
            function () use ($authManager) {
                return $authManager->getPermissions();
            }
        );
    }
}
