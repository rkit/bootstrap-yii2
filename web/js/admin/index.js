// Vendor
var $ = window.jQuery = window.$ = require('jquery');
require('bootstrap/dist/js/bootstrap.min.js');
require('bootstrap/dist/css/bootstrap.min.css');
require('file?name=[name].[ext]!bootstrap/dist/css/bootstrap.css.map');
require('yii2-pjax');
require('./../../../vendor/yiisoft/yii2/assets/yii.js');
require('./../../../vendor/yiisoft/yii2/assets/yii.validation.js');
require('./../../../vendor/yiisoft/yii2/assets/yii.activeForm.js');
require('./../../../vendor/yiisoft/yii2/assets/yii.gridView.js');

// Application
var app = require('./app');

$(function() {
  app.init();
});

// CSS
require('./../../css/admin/animate.css');
require('./../../css/admin/fileapi.css');
require('./../../css/admin/style.css');
