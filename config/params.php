<?php

$assets = '1';

if (YII_ENV !== 'test') {
    $assets = @file_get_contents(__DIR__ . '/../web/assets/manifest.json');
    $assets = (array)json_decode($assets);
}

return [
    'mainTimeZone' => 'Europe/Moscow',
    'user.tokenExpire' => 3600,
    'assets' => $assets
];
