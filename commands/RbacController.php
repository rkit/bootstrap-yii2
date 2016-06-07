<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Command for create permissions
 */
class RbacController extends Controller
{
    private $permissions = [];

    public function init()
    {
        // ALL PERMISSIONS!
        $this->permissions = [
            'AdminModule' => Yii::t('app', 'Access to the Control Panel'),
            // for actions
            'ACTION_AdminNews' => Yii::t('app', 'Control Panel / News'),
            'ACTION_AdminTags' => Yii::t('app', 'Control Panel / Tags'),
            'ACTION_AdminRoles' => Yii::t('app', 'Control Panel / Roles'),
            'ACTION_AdminUsers' => Yii::t('app', 'Control Panel / Users'),
            'ACTION_AdminCities' => Yii::t('app', 'Control Panel / Cities'),
            'ACTION_AdminRegions' => Yii::t('app', 'Control Panel / Regions'),
            'ACTION_AdminSettings' => Yii::t('app', 'Control Panel / Settings'),
            'ACTION_AdminCountries' => Yii::t('app', 'Control Panel / Countries'),
        ];

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
