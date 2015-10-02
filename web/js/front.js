jQuery = window.jQuery = window.$ = require('jquery');

// Styles
require('file?name=[name].[ext]!bootstrap/dist/css/bootstrap.css.map');
require('bootstrap/dist/css/bootstrap.min.css');
require('./../css/front/style.css');

// Vendor
require('bootstrap/dist/js/bootstrap.min.js');
require('yii2-pjax');
require('./../../vendor/yiisoft/yii2/assets/yii.js');
require('./../../vendor/yiisoft/yii2/assets/yii.validation.js');
require('./../../vendor/yiisoft/yii2/assets/yii.activeForm.js');

// Application
require('./front/index');
