<?php

$assets = @file_get_contents(__DIR__ . '/../web/assets/manifest.json');
$assets = (array)json_decode($assets);

return [
    'user.tokenExpire' => 3600,
    'assets' => $assets
];
