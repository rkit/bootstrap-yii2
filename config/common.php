<?php

/**
 * Urls
 */

$config['components']['urlManager']['rules'] = [
    // frontend
    '/' => 'index/index',
    'auth-social' => 'index/auth-social',
    'signup' => 'index/signup',
    'signup-provider' => 'index/signup-provider',
    'login' => 'index/login',
    'logout' => 'index/logout',
    'reset-password' => 'index/reset-password',
    'request-password-reset' => 'index/request-password-reset',
    'confirm-email' => 'index/confirm-email',
    'confirm-request' => 'index/confirm-request',

    // background call


    // admin
    'admin' => 'admin/index/index',
    'admin/<controller>' => 'admin/<controller>',
    'admin/<controller>/<action>' => 'admin/<controller>/<action>',
];

/**
 * Translations
 */

$config['components']['i18n'] = [
    'translations' => [
        'app*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/messages',
            'sourceLanguage' => 'en-US',
        ],
    ],
 ];

/**
 * FileManager
 */

$config['components']['fileManager'] = [
    'class' => 'rkit\filemanager\FileManager',
    // 'sessionName' => 'filemanager.uploads',
];

/**
 * FlySystem
 */

$config['components']['localFs'] = [
    'class' => 'creocoder\flysystem\LocalFilesystem',
    'path' => '@webroot/uploads',
];

/**
 * Containers
 */

$config['container'] = [
    'definitions' => [
        'yii\widgets\LinkPager' => [
            'maxButtonCount' => 5,
            'nextPageLabel'  => '&rarr;',
            'prevPageLabel'  => '&larr;',
            'firstPageLabel' => '&lArr;',
            'lastPageLabel'  => '&rArr;',
        ],
        'app\services\SocialAuth' => [
            'parsers' => [
                'vkontakte' => 'app\parsers\auth\Vkontakte',
                'twitter' => '\app\parsers\auth\Twitter',
                'facebook' => '\app\parsers\auth\Facebook',
            ]
        ],
    ],
];


return $config;
