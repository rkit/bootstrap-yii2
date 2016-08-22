<?php

namespace app\tests\unit\commands;

use Yii;
use app\commands\RbacController;

class RbacTest extends \Codeception\Test\Unit
{
    public function testInit()
    {
        $command = new RbacController('test', 'test');
        $command->actionInit();
    }

    public function testCheckDeletePermission()
    {
        $auth = Yii::$app->authManager;
        $permission = $auth->createPermission('test');
        $auth->add($permission);

        expect_that($auth->getPermission('test'));

        $command = new RbacController('test', 'test');
        $command->actionInit();

        expect_not($auth->getPermission('test'));
    }
}
