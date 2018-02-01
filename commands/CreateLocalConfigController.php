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
     * @var string MySQL host
     */
    public $MYSQL_HOST;
    /**
     * @var string MySQL database
     */
    public $MYSQL_DATABASE;
    /**
     * @var string MySQL user
     */
    public $MYSQL_USER;
    /**
     * @var string MySQL password
     */
    public $MYSQL_PASSWORD;
    /**
     * @var string Config file path
     */
    public $path;

    public function options($actionId = '')
    {
        return [
            'path', 
            'MYSQL_HOST',
            'MYSQL_DATABASE',
            'MYSQL_USER',
            'MYSQL_PASSWORD',
        ];
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
            $this->fill($dist);

            $this->stdout("Created successfully!\n", Console::FG_GREEN);
        } else {
            $this->stdout("Config file is exist!\n", Console::FG_RED);
        }

        return ExitCode::OK;
    }

    private function fill(string $file)
    {
        $contents = file_get_contents($file);
        $settings = array_merge($this->envVars(), $this->getOptionValues(''));

        foreach ($settings as $var => $value) {
            $contents = str_replace('%' . $var  . '%', $value, $contents);
        }

        file_put_contents($file, $contents);
    }

    private function envVars()
    {
        $envFile = Yii::getAlias('@app/.env');
        if (file_exists($envFile)) {
            return parse_ini_file($envFile);
        }
        return [];
    }
}
