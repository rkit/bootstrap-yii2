<?php

/**
 * Config for Test Application
 */

defined('YII_ENV_MODE') or define('YII_ENV_MODE', 'console');

$local = require __DIR__ . '/local/main.php';
$local['components']['db']['dsn'] .= '_tests';

$params = require __DIR__ . '/params.php';

return [
    'id' => 'tests',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'index/index',
    'language' => 'en-US',

    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],

    'components' => [
        'db' => $local['components']['db'],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            'rules' => [
                '' => 'index/index',
            ],
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\entity\User',
            'enableAutoLogin' => false,
            'loginUrl' => ['/index/login']
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cacheFileSuffix' => '.test.bin',
        ],
        'i18n' => [
             'translations' => [
                 'app*' => [
                     'class' => 'yii\i18n\PhpMessageSource',
                     // 'basePath' => '@app/messages',
                     // 'sourceLanguage' => 'en-US',
                     'fileMap' => [
                         'app' => 'app.php',
                         'app/errors' => 'errors.php',
                         'app/messages' => 'messages.php',
                     ],
                 ],
             ],
         ],
         'settings' => [
             'class' => 'rkit\settings\Settings',
         ],
         'assetManager' => [
             'bundles' => false,
         ],
         'authManager' => [
             'class' => 'yii\rbac\DbManager',
         ],
         'authClientCollection' => [
             'class' => 'yii\authclient\Collection',
         ],
         'fileManager' => [
             'class' => 'rkit\filemanager\FileManager',
             // 'sessionName' => 'filemanager.uploads',
         ],
         'localFs' => [
             'class' => 'creocoder\flysystem\LocalFilesystem',
             'path' => '@app/tests/_tmp/files/uploads',
         ],
    ],
    'params' => $params,
];
