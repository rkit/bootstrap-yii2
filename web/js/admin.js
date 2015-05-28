jQuery = window.jQuery = window.$ = require('jquery');

// Vendor
require('bootstrap');
require('yii2-pjax');
require('./../../vendor/yiisoft/yii2/assets/yii.js');
require('./../../vendor/yiisoft/yii2/assets/yii.validation.js');
require('./../../vendor/yiisoft/yii2/assets/yii.activeForm.js');
require('./../../vendor/yiisoft/yii2/assets/yii.gridView.js');

// SourceMap
require('file?name=[name].[ext]!./../../vendor/bower/bootstrap/dist/css/bootstrap.css.map');

// Application
var form = require('./admin/form');
var app = require('./admin/app');

$(function() {
  form.init();
  app.init();
});

// Styles
require('./../css/admin/style.css');
