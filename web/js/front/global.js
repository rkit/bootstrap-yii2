/**
 * Global methods
 */
var global = {

    init: function() {
        this.settings();
    },

    settings: function() {
        $.ajaxSetup({
            type: "POST",
            dataType: "json",
        });
        
        $(document).ajaxError(function(event, jqxhr, settings, exception) {
            console.log(jqxhr.responseText);
        });
        
        // Adding a csrf-token in the request
        $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
            if ((originalOptions.type !== undefined && 
                 originalOptions.type.toLowerCase() == 'post') ||
                (options.type !== undefined && options.type.toLowerCase() == 'post')) {
                var data = originalOptions.data;

                if (originalOptions.data !== undefined) {
                    if (Object.prototype.toString.call(originalOptions.data) === '[object String]') {
                        data = $.deparam(originalOptions.data);
                    }
                } else {
                    data = {};
                }

                try {
                    options.data = $.param($.extend(data, {
                        'csrf-token': $('meta[name="csrf-token"]').attr('content')
                    }));
                    
                } catch (e) {

                }
            }
        });
    },
}

$(function () {
    global.init();
});

/**
 * An extraction of the deparam method from Ben Alman's jQuery BBQ
 * https://github.com/chrissrogers/jquery-deparam
 */
(function(h){h.deparam=function(i,j){var d={},k={"true":!0,"false":!1,"null":null};h.each(i.replace(/\+/g," ").split("&"),function(i,l){var m;var a=l.split("="),c=decodeURIComponent(a[0]),g=d,f=0,b=c.split("]["),e=b.length-1;/\[/.test(b[0])&&/\]$/.test(b[e])?(b[e]=b[e].replace(/\]$/,""),b=b.shift().split("[").concat(b),e=b.length-1):e=0;if(2===a.length)if(a=decodeURIComponent(a[1]),j&&(a=a&&!isNaN(a)?+a:"undefined"===a?void 0:void 0!==k[a]?k[a]:a),e)for(;f<=e;f++)c=""===b[f]?g.length:b[f],m=g[c]=
f<e?g[c]||(b[f+1]&&isNaN(b[f+1])?{}:[]):a,g=m;else h.isArray(d[c])?d[c].push(a):d[c]=void 0!==d[c]?[d[c],a]:a;else c&&(d[c]=j?void 0:"")});return d}})(jQuery);