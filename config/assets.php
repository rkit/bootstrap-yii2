<?php

/**
 * Configuration file for the "yii asset" console command.
 */

Yii::setAlias('@webroot', realpath(__DIR__ . '/../web'));
Yii::setAlias('@web', '/');

Yii::setAlias('@bower', '@vendor/bower-asset');
Yii::setAlias('@npm', '@vendor/npm-asset');

if (!file_exists(Yii::getAlias('@webroot/assets/js'))) {
    mkdir(Yii::getAlias('@webroot/assets/js'));
}
if (!file_exists(Yii::getAlias('@webroot/assets/css'))) {
    mkdir(Yii::getAlias('@webroot/assets/css'));
}

return [
    'jsCompressor' => 'node_modules/uglify-js/bin/uglifyjs {from} -o {to}',
    'cssCompressor' => 'node_modules/uglifycss/uglifycss {from} --output {to}',
    // Whether to delete asset source after compression:
    'deleteSource' => false,
    // The list of asset bundles to compress:
    'bundles' => [
        'app\assets\AppAsset',
        'app\modules\admin\assets\AppAsset',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'common' => [
            'class' => 'yii\web\AssetBundle',
            'depends' => [
                'yii\web\JqueryAsset',
                'yii\web\YiiAsset',
                'rkit\yii2\plugins\ajaxform\Asset',
            ],
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/common-{hash}.js',
            'css' => 'css/common-{hash}.css',
        ],
        'front' => [
            'class' => 'yii\web\AssetBundle',
            'depends' => [
                'app\assets\AppAsset',
            ],
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/front-{hash}.js',
            'css' => 'css/front-{hash}.css',
        ],
        'admin' => [
            'class' => 'yii\web\AssetBundle',
            'depends' => [
                'yii\bootstrap\BootstrapAsset',
                'yii\bootstrap\BootstrapPluginAsset',
                'app\modules\admin\assets\AppAsset'
            ],
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/admin-{hash}.js',
            'css' => 'css/admin-{hash}.css',
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'linkAssets' => true,
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];
