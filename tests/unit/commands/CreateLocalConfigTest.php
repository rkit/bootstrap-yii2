<?php

namespace app\tests\unit\commands;

use Yii;
use app\commands\CreateLocalConfigController;

class CreateLocalConfigTest extends \Codeception\Test\Unit
{
    public function testInit()
    {
        $command = new CreateLocalConfigController('test', 'test');
        $command->actionInit('test.php');

        $file = Yii::getAlias('@app') . '/config/local/test.php';

        expect(file_exists($file))->true();
        unlink($file);
        expect(file_exists($file))->false();
    }
}
