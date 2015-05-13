<?php

return [
    'mainTimeZone' => 'Europe/Moscow',
    'user.tokenExpire' => 3600,
    'assets.hash' => YII_ENV == 'test' ? '1' : @file_get_contents(__DIR__ . '/../web/assets/hash')
];
