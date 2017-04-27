<?php

/**
 * Translations
 */

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
    ],
];

return $config;
