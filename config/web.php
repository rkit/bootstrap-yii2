<?php

/**
 * Config for Web Application
 */

define('YII_ENV_MODE', 'web');

$params = require __DIR__ . '/params.php';
$bundles = YII_ENV_PROD ? require __DIR__ . '/local/assets.php' : [];

$config = [
    'id'             => 'iNCVrvPTpDQuWpdnqqz6NPXeUHsRQoV3',
    'basePath'       => dirname(__DIR__),
    'bootstrap'      => ['log'],
    'name'           => 'Bootstrap',
    'defaultRoute'   => 'index/index',
    'sourceLanguage' => 'en',
    'language'       => 'ru',
    'timeZone'       => 'Europe/Moscow',

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mwsCLjohbWvqV8sLaHXebbZxDhmHEHF3',
            'enableCsrfValidation' => true,

            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'sizeFormatBase' => 1000,
            'dateFormat' => 'php:d M Y',
            'datetimeFormat' => 'php:d M Y, H:i',
            'timeFormat' => 'php:H:i:s',
            'currencyCode' => 'RUB',
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\entity\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/index/login']
        ],
        'errorHandler' => [
            'errorAction' => YII_ENV_DEV ? null : '/index/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:400',
                        'yii\web\HttpException:401',
                        'yii\web\HttpException:403',
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:422',
                    ],
                ],
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
            'bundles' => $bundles,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'settings' => [
            'class' => 'rkit\settings\Settings',
        ],
        'notify' => [
            'class' => 'app\components\Notify',
        ],
    ],
    'params' => $params,
];

require_once __DIR__ . '/common.php';
require_once __DIR__ . '/local/main.php';

/**
 * Transliterator
 */

\yii\helpers\Inflector::$transliterator =
'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';

/**
 * Maintenance mode
 */

if (file_exists($config['basePath'] . '/runtime/maintenance')) {
    if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
        $config['catchAll'] = ['index/maintenance'];
    }
}

return $config;
