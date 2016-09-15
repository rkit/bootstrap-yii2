<?php

namespace app\tests\unit\commands;

use Yii;
use app\commands\CreateLocalConfigController;

class CreateLocalConfigTest extends \Codeception\Test\Unit
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage `path` should be specified
     */
    public function testWithoutPath()
    {
        $command = new CreateLocalConfigController('test', 'test');
        $command->beforeAction('test');
        $command->actionInit();
    }

    public function testCountOptions()
    {
        $command = new CreateLocalConfigController('test', 'test');
        expect_that(is_array($command->options()));
        expect(count($command->options()))->equals(1);
    }

    public function testCreate()
    {
        $config = Yii::getAlias('@app/config/local/test.php');

        $command = new CreateLocalConfigController('test', 'test');
        $command->path = $config;
        $command->beforeAction('test');
        $command->actionInit();

        expect(file_exists($config))->true();
        unlink($config);
        expect(file_exists($config))->false();
    }
}
