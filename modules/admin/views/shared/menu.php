<?php

use yii\widgets\Menu;

$permissions = Yii::$app->cache->get('rbac-permissions');
if (!$permissions) {
    $permissions = Yii::$app->authManager->getPermissions();
    Yii::$app->cache->set('rbac-permissions', $permissions);
}

$permissions = Yii::$app->user->identity->isSuperUser()
    ? $permissions
    : Yii::$app->authManager->getPermissionsByRole(Yii::$app->user->identity->role);

$items = [
    [
        'label'  => Yii::t('app', 'News'),
        'url'    => ['/admin/news/index'],
        'visible' => array_key_exists('ACTION_AdminNews', $permissions),
        'active' => in_array(Yii::$app->request->pathInfo, [
            'admin/news',
            'admin/news/index',
            'admin/news/edit'
        ])
    ],

    [
        'label'  => Yii::t('app', 'Users'),
        'url'    => ['/admin/users/index'],
        'visible' => array_key_exists('ACTION_AdminUsers', $permissions),
        'active' => in_array(Yii::$app->request->pathInfo, [
            'admin/users',
            'admin/users/index',
            'admin/users/edit',
            'admin/users/profile'
        ])
    ],

    [
        'label' => Yii::t('app', 'Settings'),
        'url' => '#_',
        'options' => ['class' => 'submenu-header'],
        'visible' =>
            array_key_exists('ACTION_AdminSettings', $permissions) ||
            array_key_exists('ACTION_AdminRoles', $permissions) ||
            array_key_exists('ACTION_AdminTags', $permissions),
        'items' => [
            [
                'label' => Yii::t('app', 'Main settings'),
                'url' => ['/admin/settings/index'],
                'visible' => array_key_exists('ACTION_AdminSettings', $permissions),
            ],
            [
                'label'  => Yii::t('app', 'Roles'),
                'url'    => ['/admin/roles/index'],
                'visible' => array_key_exists('ACTION_AdminRoles', $permissions),
                'active' => in_array(Yii::$app->request->pathInfo, [
                    'admin/roles',
                    'admin/roles/index',
                    'admin/roles/edit'
                ])
            ],
        ]
    ],
];

?>

<?= Menu::widget([
     'encodeLabels' => false,
     'activateParents' => true,
     'submenuTemplate' => '<ul class="nav">{items}</ul>',
     'items' => $items,
     'options' => ['class' => 'nav sidenav', 'id' => 'menu']]) ?>
