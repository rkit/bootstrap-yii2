<?php

namespace app\tests\unit\commands;

use Yii;
use app\commands\RbacController;

class RbacTest extends \Codeception\Test\Unit
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage `path` should be specified
     */
    public function testWithoutPath()
    {
        $command = new RbacController('test', 'test');
        $command->beforeAction('test');
        $command->actionUp();
    }

    public function testCountOptions()
    {
        $command = new RbacController('test', 'test');
        expect_that(is_array($command->options()));
        expect(count($command->options()))->equals(1);
    }

    public function testSuccess()
    {
        $config = Yii::getAlias('@app/tests/_data/rbac/permissions.php');

        $command = new RbacController('test', 'test');
        $command->path = $config;
        $command->beforeAction('test');
        $command->actionUp();

        $auth = Yii::$app->authManager;

        $permissions = $auth->getPermissions();
        $permissionsInConfig = require $config;
        $permissionsInDb = [];

        foreach ($permissions as $permission) {
            $permissionsInDb[$permission->name] = $permission->description;
        }

        expect(count($permissionsInDb))->equals(5);
        expect($permissionsInDb)->equals($permissionsInConfig);
    }

    public function testCheckFakePermission()
    {
        $config = Yii::getAlias('@app/tests/_data/rbac/permissions.php');

        $auth = Yii::$app->authManager;
        $permission = $auth->createPermission('test');
        $auth->add($permission);

        expect_that($auth->getPermission('test'));

        $command = new RbacController('test', 'test');
        $command->path = $config;
        $command->beforeAction('test');
        $command->actionUp();

        expect_not($auth->getPermission('test'));
    }
}
