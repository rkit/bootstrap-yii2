var app = {
  init: function() {
    this.ajaxSetup();
  },

  ajaxSetup: function() {
    $(document).ajaxError(function(event, jqxhr) {
      console.log(jqxhr.responseText);
    });
  },
};

module.exports = app;
