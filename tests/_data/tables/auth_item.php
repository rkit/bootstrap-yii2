<?php

use \app\models\User;

$permissions = require __DIR__ . '/../../../config/rbac/permissions.php';
$items = [];

foreach ($permissions as $name => $description) {
    $items[] = [
        'name' => $name,
        'description' => $description,
        'type' => \yii\rbac\Item::TYPE_PERMISSION,
    ];
}

return array_merge($items, [
    [
        'name' => User::ROLE_SUPERUSER,
        'description' => User::ROLE_SUPERUSER,
        'type' => \yii\rbac\Item::TYPE_ROLE,
    ],
    [
        'name' => 'Editor',
        'description' => 'Role-1',
        'type' => \yii\rbac\Item::TYPE_ROLE,
    ],
]);
