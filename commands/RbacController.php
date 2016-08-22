<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Command for create permissions
 * @see config/permissions.php
 */
class RbacController extends Controller
{
    private $permissions = [];

    public function init()
    {
        $this->permissions = require __DIR__ . '/../config/permissions.php';

        Yii::$app->cache->delete('rbac-permissions');
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $currentPermissions = $auth->getPermissions();

        foreach ($currentPermissions as $currentPermission) {
            if (!isset($this->permissions[$currentPermission->name])) {
                $auth->remove($currentPermission);
            }
        }

        foreach ($this->permissions as $name => $description) {
            $isNew = !isset($currentPermissions[$name]);

            $permission = $isNew ? $auth->createPermission($name) : $auth->getPermission($name);
            $permission->description = $description;

            $isNew ? $auth->add($permission) : $auth->update($name, $permission);
        }

        $this->stdout("RBAC updated\n", Console::FG_GREEN);
    }
}
