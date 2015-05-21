var app = {
  init: function() {
    this.ajaxSetup();
    this.binding();
  },

  ajaxSetup: function() {
    $.ajaxSetup({
      type: 'POST',
      dataType: 'json'
    });

    $(document).ajaxError(function(event, jqxhr, settings, exception) {
      console.log(jqxhr.responseText);
    });
  },

  binding: function() {

  }
}

module.exports = app;
