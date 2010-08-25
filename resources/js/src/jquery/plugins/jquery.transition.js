(function($) {  
    $.fn.transition = function(settings) {
        var config = {  
            transition_in_props:    {opacity: 1},       // transition in properties to animate
            transition_out_props:   {opacity: 0},       // transition out properties to animate
            
            transition_in_speed:    200,                // transition in speed
            transition_out_speed:   200,                // transition out speed
            
            transition_in_easing:   'linear',           // transition in easing 
            transition_out_easing:  'linear',           // transition out easing
            
            load: function() {},                        // function to execute when transition in completes
            load_params: []                             // array of params to send to load function
        };

        if (settings) $.extend(config, settings);

        this.each(function(i) { 
            var $this = $(this);
            
            // transition out
            $this.animate(config.transition_out_props, config.transition_out_speed, config.transition_out_easing,  function() {
                $this.trigger("transition_out_complete");
            
                // call load
                if($.isFunction(config.load))
                {
                    config.load(this, config.load_params);

                    // transition in
                    $this.animate(config.transition_in_props, config.transition_in_speed, config.transition_in_easing, function() {
                        $this.trigger("transition_in_complete");
                    });
                }
             });
        });
        
        return this;
    };
})(jQuery);