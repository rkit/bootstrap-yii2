<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id'             => 'iNCVrvPTpDQuWpdnqqz6NPXeUHsRQoV3',
    'basePath'       => dirname(__DIR__),
    'bootstrap'      => ['log'],
    'name'           => 'Bootstrap',
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
                'yii\web\YiiAsset' => false,
                'yii\web\JqueryAsset' => false,
                'yii\widgets\PjaxAsset' => false,
                'yii\widgets\ActiveFormAsset' => false,
                'yii\grid\GridViewAsset' => false,
                'yii\validators\ValidationAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\bootstrap\BootstrapThemeAsset' => false,
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\jui\JuiAsset' => false
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'settings' => [
            'class' => 'app\components\Settings',
        ],
        'notify' => [
            'class' => 'app\components\Notify',
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
        ],
        'fileManager' => [
            'class' => 'rkit\filemanager\FileManager',
            'uploadDirProtected' => '@runtime',
            'uploadDirUnprotected' => '@app/web',
            'publicPath' => 'uploads/files',
            'ownerTypes' => [
                'news.text' => 1,
                'news.preview' => 2,
                'news.gallery' => 3,
                'user_profile.photo' => 4
            ]
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
