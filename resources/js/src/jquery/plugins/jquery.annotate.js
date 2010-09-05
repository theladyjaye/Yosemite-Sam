(function($){
	
	$.fn.annotatableImage = function(annotationCallback, options) {
		var defaults = {
	    xPosition: 'middle',
	    yPosition: 'middle'
	  };
		var options = $.extend(defaults, options);
	
		var annotations = [];
		var image = $('img', this)[0];
		var date = new Date();
		var startTime = date.getTime();
	
		this.mousedown(function(event){
			if (event.target == image) {
				event.preventDefault();
			
				var element = annotationCallback();
				annotations.push(element);
				$(this).append(element);
			
				element.positionAtEvent(event, options.xPosition, options.yPosition);
				element.mousedown(function(evt) {
					$(this).trigger("resize");
				}).mousedown();
				var date = new Date();
				element.data('responseTime', date.getTime() - startTime);
			}
		});		
		return this;
	};

	$.fn.addAnnotations = function(annotationCallback, annotations, options) {
		var container = this;
		var containerHeight = $(container).height();
		var defaults = {
	    	xPosition: 'middle',
	    	yPosition: 'middle',
			height: containerHeight
	  	};
		var options = $.extend(defaults, options);
	
		$.each(annotations, function(i) {
			var element = annotationCallback($.extend(options, annotations[i]));
			element.css({position: 'absolute'});
		
			$(container).append(element);
		
			var left = (this.x * $(container).width()) - ($(element).xOffset(options.xPosition));
			var top = (this.y * options.height) - ($(element).yOffset(options.yPosition));
			
			if (this.width && this.height) {
				element.css({width: this.width + 'px', height: this.height + 'px'});
				element.find(".frm-note").css({top: this.height + 10 + "px"});
			}
			/*
			if (this.width && this.height) {
				var width = (this.width * $(container).width());
				var height = (this.height * $(container).height());
				element.css({width: width + 'px', height: height + 'px'});
			}
			*/
			element.css({ left: left + 'px', top: top + 'px'});
			if (top > containerHeight) {
				element.hide();
			}
		});
		
		return this;
	};

	$.fn.positionAtEvent = function(event, xPosition, yPosition) {
		var container = $(this).parent('div');
		$(this).css('left', event.pageX - container.offset().left - ($(this).xOffset(xPosition)) + 'px');
		$(this).css('top', event.pageY - container.offset().top - ($(this).yOffset(yPosition)) + 'px');
		$(this).css('position', 'absolute');
	};

	$.fn.serializeAnnotations = function(xPosition, yPosition) {
		var annotations = [];
		this.each(function(){
//			annotations.push({x: $(this).relativeX(xPosition), y: $(this).relativeY(yPosition), response_time: $(this).data('responseTime')});
			annotations.push($(this).serializeAnnotation(xPosition, yPosition));
		});
		return annotations;
	};
	
	$.fn.serializeAnnotation = function(xPosition, yPosition) {
		return {x: $(this).relativeX(xPosition), y: $(this).relativeY(yPosition), response_time: $(this).data('responseTime'), width: $(this).width(), height: $(this).height()};
	}

	$.fn.relativeX = function(xPosition) {
		var left = $(this).coordinates().x + ($(this).xOffset(xPosition));
		var width = $(this).parent().width();
		return left / width;
	}

	$.fn.relativeY = function(yPosition) {
		var top = $(this).coordinates().y + ($(this).yOffset(yPosition));
		var height = $(this).parent().height();
		return top / height;
	}

	$.fn.relativeWidth = function() {
		return $(this).width() / $(this).parent().width();
	}

	$.fn.relativeHeight = function() {
		return $(this).height() / $(this).parent().height();
	}

	$.fn.xOffset = function(xPosition) {
		switch (xPosition) {
			case 'left': return 0; break;
			case 'right': return $(this).width(); break;
			default: return $(this).width() / 2; // middle
		}
	};

	$.fn.yOffset = function(yPosition) {
		switch (yPosition) {
			case 'top': return 0; break;
			case 'bottom': return $(this).height(); break;
			default: return $(this).height() / 2; // middle
		}
	};
	
	$.fn.coordinates = function() {
		return {x: parseInt($(this).css('left').replace('px', '')), y: parseInt($(this).css('top').replace('px', ''))};
	};
	
})(jQuery);