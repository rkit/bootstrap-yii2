var app = {
  init: function() {
    this.ajaxSetup();
    this.binding();
  },

  ajaxSetup: function() {
    $.ajaxSetup({
      type: 'POST',
      dataType: 'json',
    });

    $(document).ajaxError(function(event, jqxhr) {
      console.log(jqxhr.responseText);
    });
  },

  binding: function() {

  },
};

module.exports = app;
