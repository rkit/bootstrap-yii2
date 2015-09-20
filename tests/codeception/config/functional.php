<?php

$_SERVER['SCRIPT_FILENAME'] = YII_TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = YII_TEST_ENTRY_URL;

$baseConfig = require __DIR__ . '/../../../config/web.php';
$localConfig = require __DIR__ . '/../../../config/local/config.php';
$localConfig['components']['db']['dsn'] .= '_tests';

/**
 * Application configuration for functional tests
 */
return yii\helpers\ArrayHelper::merge(
    $baseConfig,
    $localConfig,
    require(__DIR__ . '/config.php'),
    [
        'components' => [
            'request' => [
                // it's not recommended to run functional tests with CSRF validation enabled
                'enableCsrfValidation' => false,
                // but if you absolutely need it set cookie domain to localhost
                /*
                'csrfCookie' => [
                    'domain' => 'localhost',
                ],
                */
            ],
        ],
    ]
);
