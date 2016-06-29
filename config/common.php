<?php

/* Translations
-------------------------------------------------- */

$config['components']['i18n'] = [
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
 ];

/* File Manager
-------------------------------------------------- */

$config['components']['fileManager'] = [
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
];

return $config;
