<?php
/**
 * Application configuration shared by all test types
 */

return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=127.0.0.1;dbname=bootstrap2_tests',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
