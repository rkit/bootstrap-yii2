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
            $this->stdout("Created successfully!\n", Console::FG_GREEN);
        } else {
            $this->stdout("Config file is exist!\n", Console::FG_RED); // @codeCoverageIgnore
        }
    }
}
