<?php

$local = require __DIR__ . '/local/config.php';
$local['components']['db']['dsn'] .= '_tests';

$params = require __DIR__ . '/params.php';

/**
 * Application configuration shared by all test types
 */
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
            'identityClass' => 'app\models\User',
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
         'cache' => [
             'class' => 'yii\caching\FileCache',
         ],
         'settings' => [
             'class' => 'rkit\settings\Settings',
         ],
         'notify' => [
             'class' => 'app\components\Notify',
         ],
         'authManager' => [
             'class' => 'yii\rbac\DbManager',
             'cache' => 'cache',
         ],
         'authClientCollection' => [
             'class' => 'yii\authclient\Collection',
         ],
         'fileManager' => [
             'class' => 'rkit\filemanager\FileManager',
             'uploadDirProtected' => '@app/runtime',
             'uploadDirUnprotected' => '@app/web',
             'publicPath' => 'uploads',
             'ownerTypes' => [
                 'news.text' => 1,
                 'news.preview' => 2,
                 'news.gallery' => 3,
                 'user_profile.photo' => 4,
             ]
         ],
    ],
    'params' => $params,
];
