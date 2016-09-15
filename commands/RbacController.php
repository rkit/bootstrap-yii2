<?php

namespace app\commands;

use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Command for create permissions
 * @see config/permissions.php
 */
class RbacController extends Controller
{
    /**
     * @var authManager
     */
    private $auth;
    /**
     * @var string
     */
    public $path;

    public function options()
    {
        return ['path'];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (empty($this->path)) {
                throw new InvalidConfigException('`path` should be specified');
            }
        }
        return true;
    }

    public function init()
    {
        $this->auth = Yii::$app->authManager;
        Yii::$app->cache->delete('rbac-permissions');
    }

    public function actionInit()
    {
        $currentPermissions = $this->auth->getPermissions();
        $newPermissions = require Yii::getAlias($this->path);

        $this->cleanUnusedPermissions($currentPermissions, $newPermissions);
        $this->updatePermissions($currentPermissions, $newPermissions);

        $this->stdout("Done!\n", Console::FG_GREEN);
    }

    private function cleanUnusedPermissions($currentPermissions, $newPermissions)
    {
        foreach ($currentPermissions as $currentPermission) {
            if (!isset($newPermissions[$currentPermission->name])) {
                $this->auth->remove($currentPermission);
            }
        }
    }

    private function updatePermissions($currentPermissions, $newPermissions)
    {
        foreach ($newPermissions as $name => $description) {
            $isNew = !isset($currentPermissions[$name]);
            if ($isNew) {
                $this->add($name, $description);
                continue;
            }
            $this->update($name, $description);
        }
    }

    private function add($name, $description)
    {
        $permission = $this->auth->createPermission($name);
        $permission->description = $description;
        $this->auth->add($permission);
        $this->stdout("Added: $name\n", Console::FG_GREEN);
    }

    private function update($name, $description)
    {
        $permission = $this->auth->getPermission($name);
        $permission->description = $description;
        $this->auth->update($name, $permission);
        $this->stdout("Updated: $name\n", Console::FG_GREEN);
    }
}
