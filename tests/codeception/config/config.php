<?php
/**
 * Application configuration shared by all test types
 */

return [
    'language' => 'en',
    'components' => [
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
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
        ]
    ],
];
