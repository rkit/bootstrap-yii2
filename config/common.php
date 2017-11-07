<?php

/**
 * Urls
 */

$config['components']['urlManager']['rules'] = [
    // frontend
    '/' => 'index/index',

    // auth
    'auth/login' => 'auth/index/login',
    'auth/signup' => 'auth/index/signup',
    'auth/confirm-request' => 'auth/index/confirm-request',
    'auth/confirm-email' => 'auth/index/confirm-email',
    'auth/request-password-reset' => 'auth/index/request-password-reset',
    'auth/reset-password' => 'auth/index/reset-password',
    'auth/social' => 'auth/social/index',
    'auth/social/signup' => 'auth/social/signup',

    // profile
    'profile/logout' => 'profile/index/logout',

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
        'app\modules\auth\services\SocialAuth' => [
            'parsers' => [
                'vkontakte' => '\app\parsers\oauth\Vkontakte',
                'twitter' => '\app\parsers\oauth\Twitter',
                'facebook' => '\app\parsers\oauth\Facebook',
            ]
        ],
    ],
    'singletons' => [
        'yii\swiftmailer\Message' => function () {
            $message = new \yii\swiftmailer\Message();
            $message->setFrom([
                Yii::$app->settings->emailMain => Yii::$app->settings->emailName
            ]);

            return $message;
        }
    ]
];


return $config;
