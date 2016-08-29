<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Command for create local config
 */
class CreateLocalConfigController extends Controller
{
    public function actionInit($name = 'config.php')
    {
        if (copy(
            Yii::getAlias('@app') . '/config/config.local',
            Yii::getAlias('@app') . '/config/local/' . $name
        )) {
            $this->stdout("Created successfully!\n", Console::FG_GREEN);
        }
        $this->stdout("Could not create\n", Console::FG_RED); // @codeCoverageIgnore
    }
}
