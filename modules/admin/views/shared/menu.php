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
        'label' => Yii::t('app', 'Geo'), 
        'url' => '#_',
        'options' => ['class' => 'submenu-header'],
        'visible' =>  
            array_key_exists('ACTION_AdminCountries', $permissions) ||
            array_key_exists('ACTION_AdminRegions', $permissions) ||
            array_key_exists('ACTION_AdminCities', $permissions),
        'items' => [
            [
               'label'   => Yii::t('app', 'Countries'), 
               'url'     => ['/admin/countries/index'], 
               'visible' => array_key_exists('ACTION_AdminCountries', $permissions),
               'active' => in_array(Yii::$app->request->pathInfo, [
                   'admin/countries',
                   'admin/countries/index', 
                   'admin/countries/edit'
               ])
           ],
           [
               'label'   => Yii::t('app', 'Regions'), 
               'url'     => ['/admin/regions/index'],
               'visible' => array_key_exists('ACTION_AdminRegions', $permissions),
               'active' => in_array(Yii::$app->request->pathInfo, [
                   'admin/regions',
                   'admin/regions/index', 
                   'admin/regions/edit'
               ])
           ],
           [
               'label'   => Yii::t('app', 'Cities'), 
               'url'     => ['/admin/cities/index'],
               'visible' => array_key_exists('ACTION_AdminCities', $permissions),
               'active' => in_array(Yii::$app->request->pathInfo, [
                   'admin/cities',
                   'admin/cities/index', 
                   'admin/cities/edit'
               ])
           ],
        ]
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
            [
                'label'  => Yii::t('app', 'Tags'), 
                'url'    => ['/admin/tags/index'],
                'visible' => array_key_exists('ACTION_AdminTags', $permissions),
                'active' => in_array(Yii::$app->request->pathInfo, [
                    'admin/tags',
                    'admin/tags/index', 
                    'admin/tags/edit'
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
     'options' => ['class' => 'nav sidenav']]) ?>