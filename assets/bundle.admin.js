jQuery = window.jQuery = window.$ = require('jquery');

// Vendor
require('bootstrap');
require('yii2-pjax');

require('./../vendor/yiisoft/yii2/assets/yii.js');
require('./../vendor/yiisoft/yii2/assets/yii.validation.js');
require('./../vendor/yiisoft/yii2/assets/yii.activeForm.js');
require('./../vendor/yiisoft/yii2/assets/yii.gridView.js');

// Styles
require('./../web/css/admin/style.css');

// SourceMap
require('file?name=[name].[ext]!./../vendor/bower/bootstrap/dist/css/bootstrap.css.map');

// Application
var form = require('./../web/js/admin/form');
var app = require('./../web/js/admin/app');

$(function() {
  form.init();
  app.init();
});
