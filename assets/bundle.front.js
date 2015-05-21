jQuery = window.jQuery = window.$ = require('jquery');

// Vendor
require('bootstrap');
require('yii2-pjax');

require('./../vendor/yiisoft/yii2/assets/yii.js');
require('./../vendor/yiisoft/yii2/assets/yii.validation.js');
require('./../vendor/yiisoft/yii2/assets/yii.activeForm.js');

// Styles
require('./../web/css/front/style.css');

// SourceMap
require('file?name=[name].[ext]!./../vendor/bower/bootstrap/dist/css/bootstrap.css.map');

// Application
var app = require('./../web/js/front/app');

$(function() {
  app.init();
});
