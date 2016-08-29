<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Command for enabling and disabling maintenance mode
 */
class MaintenanceController extends Controller
{
    public function actionOff()
    {
        $this->stdout("Trying disabling maintenance mode...\n");
        $file = $this->resolveFile();

        if (file_exists($file)) {
            unlink($file);
            $this->stdout("Done\n", Console::FG_GREEN);
            return true;
        }
        $this->stdout("Application is NOT in maintenance mode.\n", Console::FG_YELLOW);
        return false;
    }

    public function actionOn()
    {
        $this->stdout("Trying enabling maintenance mode...\n");
        $file = $this->resolveFile();

        if (!file_exists($file)) {
            file_put_contents($file, time());
            $this->stdout("Done\n", Console::FG_GREEN);
            return true;
        }
        $this->stdout("Application is already in maintenance mode.\n", Console::FG_YELLOW);
        return false;
    }

    /**
     * Returns the path to the 'maintenance' file
     *
     * @return string
     */
    private function resolveFile()
    {
        return Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'maintenance';
    }
}
