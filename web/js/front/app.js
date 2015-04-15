var app = {
    init: function() {
        this.ajaxSetup();
    },
    
    ajaxSetup: function() {
        $.ajaxSetup({
            type: "POST",
            dataType: "json",
        });
        
        $(document).ajaxError(function(event, jqxhr, settings, exception) {
            console.log(jqxhr.responseText);
        });
    }
}

$(function () {
    app.init();
});

module.exports = app;
