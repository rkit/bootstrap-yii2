<?php

namespace app\commands;

use Yii;
use yii\base\InvalidConfigException;
use yii\console\{Controller, ExitCode};
use yii\helpers\{Console, ArrayHelper};

/**
 * Command for create local config
 */
class CreateLocalConfigController extends Controller
{
    /**
     * @var string
     */
    public $path;

    public function options($actionId = '')
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

    public function actionIndex()
    {
        $source = Yii::getAlias('@app/config/local/main.dist');
        $dist = Yii::getAlias($this->path);

        if (!file_exists($dist)) {
            copy($source, $dist);
            $this->specifySettings($dist);

            $this->stdout("Created successfully!\n", Console::FG_GREEN);
        } else {
            $this->stdout("Config file is exist!\n", Console::FG_RED);
        }

        return ExitCode::OK;
    }

    private function specifySettings(string $file)
    {
        $contents = file_get_contents($file);
        $env = parse_ini_file(Yii::getAlias(Yii::getAlias('@app/.env')));

        foreach ($env as $var => $value) {
            $contents = str_replace('%' . $var  . '%', $value, $contents);
        }

        file_put_contents($file, $contents);
    }
}
