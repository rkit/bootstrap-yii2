<?php
/**
 * Application configuration shared by all test types
 */

return [
    'language' => 'en',
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=127.0.0.1;dbname=bootstrap_yii2_tests',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
