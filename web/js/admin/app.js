var nprogress = require('nprogress');
var form = require('./form');
require('./binding');

var app = {
  init: function() {
    form.init();
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
