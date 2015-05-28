jQuery = window.jQuery = window.$ = require('jquery');

// Vendor
require('bootstrap');
require('yii2-pjax');
require('./../../vendor/yiisoft/yii2/assets/yii.js');
require('./../../vendor/yiisoft/yii2/assets/yii.validation.js');
require('./../../vendor/yiisoft/yii2/assets/yii.activeForm.js');

// SourceMap
require('file?name=[name].[ext]!./../../vendor/bower/bootstrap/dist/css/bootstrap.css.map');

// Application
var app = require('./front/app');

$(function() {
  app.init();
});

// Styles
require('./../css/front/style.css');
