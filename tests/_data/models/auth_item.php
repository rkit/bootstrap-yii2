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
        'name' => 'EditorNews',
        'description' => 'Role-1',
        'type' => \yii\rbac\Item::TYPE_ROLE,
    ],
    [
        'name' => 'EditorTags',
        'description' => 'Role-2',
        'type' => \yii\rbac\Item::TYPE_ROLE,
    ],
    [
        'name' => 'EditorSettings',
        'description' => 'Role-3',
        'type' => \yii\rbac\Item::TYPE_ROLE,
    ],
]);
