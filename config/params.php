<?php

$assets = [];
$assetsManifest = __DIR__ . '/../web/assets/manifest.json';
if (file_exists($assetsManifest)) {
    $assets = file_get_contents($assetsManifest);
    $assets = (array)json_decode($assets);
}

return [
    'user.tokenExpire' => 3600,
    'assets' => $assets
];
