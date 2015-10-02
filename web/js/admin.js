jQuery = window.jQuery = window.$ = require('jquery');

// Vendor
require('bootstrap/dist/js/bootstrap.min.js');
require('yii2-pjax');
require('./../../vendor/yiisoft/yii2/assets/yii.js');
require('./../../vendor/yiisoft/yii2/assets/yii.validation.js');
require('./../../vendor/yiisoft/yii2/assets/yii.activeForm.js');
require('./../../vendor/yiisoft/yii2/assets/yii.gridView.js');

// Application
require('./admin/index');

// Styles
require('file?name=[name].[ext]!bootstrap/dist/css/bootstrap.css.map');
require('bootstrap/dist/css/bootstrap.min.css');
require('./../css/admin/style.css');
