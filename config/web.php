<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id'             => 'iNCVrvPTpDQuWpdnqqz6NPXeUHsRQoV3',
    'basePath'       => dirname(__DIR__),
    'bootstrap'      => ['log'],
    'name'           => 'Bootstrap 2',
    'defaultRoute'   => 'index/index',
    'sourceLanguage' => 'en',
    'language'       => 'ru',
    'timeZone'       => 'Europe/Moscow',
    
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mwsCLjohbWvqV8sLaHXebbZxDhmHEHF3',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            
            'rules' => [
                'signup' => 'index/signup',
                'login' => 'index/login',
                'logout' => 'index/logout',
                'reset-password' => 'index/reset-password',
                'confirm-email' => 'index/confirm-email',
                'confirm-again' => 'index/confirm-again'
            ]
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
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/index/login']
        ],
        'errorHandler' => [
            'errorAction' => YII_ENV_DEV ? null : '/index/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'assetManager' => [
            'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => false,
                'yii\jui\JuiAsset' => [
                    'sourcePath' => null, 
                    'js'  => ['plugins/jquery-ui/jquery.ui-1.11.2.min.js'],
                    'css' => []
                ]
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'ruleTable'       => 'authRule',
            'itemTable'       => 'authItem', 
            'itemChildTable'  => 'authItemChild', 
            'assignmentTable' => 'authAssignment',
        ],
        'settings' => [
            'class' => 'app\components\Settings',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => '',
                    'consumerSecret' => '',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ],
        ]
    ],
    'params' => $params,
];

require_once __DIR__ . '/local/config.php';

/* Maintenance mode
-------------------------------------------------- */

if (file_exists($config['basePath'].'/runtime/maintenance')) {
    if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
        $config['catchAll'] = ['index/maintenance'];
    }
}

return $config;
