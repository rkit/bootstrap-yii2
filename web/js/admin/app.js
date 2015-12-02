var $ = require('jquery');
var nprogress = require('nprogress');
require('./binding');

var app = {
  init: function() {
    this.ajaxSetup();
  },

  ajaxSetup: function() {
    $(document).ajaxSend(function() {
      nprogress.start();
    });

    $(document).ajaxComplete(function() {
      nprogress.done();
    });

    $(document).ajaxError(function(event, jqxhr) {
      console.log(jqxhr.responseText);
    });
  },
};

module.exports = app;
