<?php

$baseConfig = require __DIR__ . '/../../../config/web.php';
$localConfig = require __DIR__ . '/../../../config/local/config.php';
$localConfig['components']['db']['dsn'] .= '_tests';

/**
 * Application configuration for unit tests
 */
return yii\helpers\ArrayHelper::merge(
    $baseConfig,
    $localConfig,
    require(__DIR__ . '/config.php'),
    [

    ]
);
