<?php

namespace app\commands;

use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Command for create local config
 */
class CreateLocalConfigController extends Controller
{
    /**
     * @var string
     */
    public $path;

    public function options()
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
    }

    public function actionInit()
    {
        $source = Yii::getAlias('@app/config/config.local');
        $dist = Yii::getAlias($this->path);

        if (!file_exists($dist)) {
            copy($source, $dist);
            return $this->stdout("Created successfully!\n", Console::FG_GREEN);
        }
        return $this->stdout("Config file is exist!\n", Console::FG_RED); // @codeCoverageIgnore
    }
}
