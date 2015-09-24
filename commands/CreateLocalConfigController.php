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
    public function actionInit()
    {
        if (copy(
            Yii::getAlias('@app') . '/config/config.local',
            Yii::getAlias('@app') . '/config/local/config.php'
        )) {
            $this->stdout("Created successfully!\n", Console::FG_GREEN);
        } else {
            $this->stdout("Could not copy\n", Console::FG_RED);
        }
    }
}
