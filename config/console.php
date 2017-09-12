<?php

/**
 * Config for Console Application
 */

defined('YII_ENV_MODE') or define('YII_ENV_MODE', 'console');

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'iNCVrvPTpDQuWpdnqqz6NPXeUHsRQoV3',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'baseUrl' => 'http://bootstrap-yii2.dev',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'settings' => [
            'class' => 'rkit\settings\Settings',
        ],
    ],
    'params' => $params,
];

require_once __DIR__ . '/common.php';
if (file_exists(__DIR__ . '/local/main.php')) {
    require_once __DIR__ . '/local/main.php';
}

return $config;
