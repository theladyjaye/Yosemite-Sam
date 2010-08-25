/**
 * Helper Plugin for jquery.tmpl.js to load and render external templates 
 * @author Phil
 * @version 1.0
 * @requires jquery.tmpl.js (http://github.com/nje/jquery-tmpl)
 */

(function($) {  
    $.fn.render_template = function(settings) {
        var config = {  
            name: 'my-template',            // name of template and name of file name
            path: 'resources/templates/',   // path to templates
            ext: '.template',               // template extensions
            data: {},                       // data to render in template
			complete: null					// on complete handler
        };

        if (settings) $.extend(config, settings);

        this.each(function(i) {
           var elt = this;
            			
            // template exists
           if($.template[config.name])
           {
				// render template with data
                $.tmpl(config.name, config.data).appendTo(elt);

				if($.isFunction(config.complete))
				{
					config.complete();
				}
           }
           // template is not cached, load it
           else
           {
                // load template
                $.get(config.path + config.name + config.ext, function(template) {
                    // put template into cache
                    $.template(config.name, template); 
										
                    // render template with data
                    $.tmpl(config.name, config.data).appendTo(elt);

					if($.isFunction(config.complete))
					{
						config.complete();
					}
                });
            }
        });
        
        return this;
    };
})(jQuery);

/*
$("#main").render_template({
  'name': 'home',
  'data': data  
});
*/