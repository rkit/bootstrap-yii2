<?php

namespace app\tests\unit\commands;

use Yii;
use app\commands\MaintenanceController;

class MaintenanceTest extends \Codeception\Test\Unit
{
    public function testOn()
    {
        $command = new MaintenanceController('test', 'test');
        expect($command->actionOn())->true();
        expect($command->actionOn())->false();
    }

    public function testOff()
    {
        $command = new MaintenanceController('test', 'test');
        expect($command->actionOff())->true();
        expect($command->actionOff())->false();
    }
}
