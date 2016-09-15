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

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
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

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function actionInit()
    {
        $source = Yii::getAlias('@app/config/config.local');
        $dist = Yii::getAlias($this->path);

        if (!file_exists($dist)) {
            copy($source, $dist);
            $this->stdout("Created successfully!\n", Console::FG_GREEN);
        } else {
            $this->stdout("Config file is exist!\n", Console::FG_RED); // @codeCoverageIgnore
        }
    }
}
