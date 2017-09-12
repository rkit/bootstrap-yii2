<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $depends = [
       'yii\web\YiiAsset',
       'rkit\yii2\plugins\ajaxform\Asset',
    ];
    public $css = [
        'css/front/style.css',
    ];
    public $js = [
    ];
}
