/**
 * Extend jQuery to add in polling method (== onEnterFrame)
 * @author Phil
 * @version 1.0
 */

(function($) {
  $.polling = function(ms, func) {
    if ($.isFunction(ms)) {
      func = ms;
      wait = 1000;
    }

    (function start_polling() {
      setTimeout(function() {
        func.call(this, start_polling)
      }, ms)
      ms = ms * 1.5
    })()
  }
})(jQuery);