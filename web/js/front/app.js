var app = (function ($) {
    var pub = {
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
    
    pub.init();
    
    return pub;
    
})(jQuery);

module.exports = app;
