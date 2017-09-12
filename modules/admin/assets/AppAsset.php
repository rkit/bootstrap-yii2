<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $depends = [
       'yii\web\YiiAsset',
       'yii\bootstrap\BootstrapAsset',
       'yii\bootstrap\BootstrapPluginAsset',
       'rkit\yii2\plugins\ajaxform\Asset',
    ];
    public $css = [
        'js/vendor/nprogress/nprogress.css',
        'css/vendor/animate/animate.css',
        'css/admin/fileapi.css',
        'css/admin/style.css',
    ];
    public $js = [
        'js/vendor/nprogress/nprogress.js',
        'js/admin/binding.js',
        'js/admin/forms.js',
    ];
}
