(function(ns) 
{
	ns.editablefields = 
	{
		main: function() 
		{		    
		    $(".editable").each(function() {
		        $(this).data("original-value", $(this).text());
		    });
		    
			$(".editable").editable(function(value, settings) {			    
			    saveChanges(this, ns.utils.getItemPath($(this)), {
			       "label": value
			    }, "label");			    
			    return value;
			}, {
		        indicator: "Saving...",
		        tooltip: "Click to edit...",
				onblur: "ignore",
		      	submit: 'Save',
		      	cancel: 'Cancel',
				name: 'new_value',				
				submitdata: function(value, settings) {
				    return {record: $.address.path()};
				},
				callback: function(value, settings) {
			        changeDeeplink(this, value);           
				},
				cssclass: 'frm-editable',
				width: 'none',
				height: 'none'
			});

			$(".editable-textarea").editable(function(value, settings) {
			    saveChanges(this, ns.utils.getItemPath($(this)), {
			       "description": value
			    }, "description");
			    return value;
			}, {
		        type: "textarea",
		        tooltip: "Click to edit...",
				onblur: "ignore",
				submit: 'Save',
		      	cancel: 'Cancel',
				name: 'new_value',
				submitdata: function(value, settings) {
					var textarea_val = $(this).find("textarea").val();
					textarea_val = textarea_val.replace(new RegExp("\\n", "g"), "<br />");
					return {new_value: textarea_val, record: $.address.path()};
				},

		        data: function(value, settings) {
		        	return $.trim(value.replace(/<br[\s\/]?>/gi, "\n"));
		        },
				cssclass: 'frm-editable',
				width: 'none',
				height: 'none'
			});
			
			$(".editable-select").editable(saveChanges, {
		        type: "select",
				data: " {'Admin':'Admin','Editor':'Editor'}",
				onblur: "ignore",
				submit: 'Save',
		      	cancel: 'Cancel',
				name: 'new_value',
				submitdata: function(value, settings) {
					var textarea_val = $(this).find("textarea").val();
					textarea_val = textarea_val.replace(new RegExp("\\n", "g"), "<br />");
					return {new_value: textarea_val, record: $.address.path()};
				},
				
				cssclass: 'frm-editable',
				width: 'none',
				height: 'none'
			});
			
			$(".editable-textarea").delegate("a", "click", function() {
				window.open($(this).attr("href"));
				return false;
			});
		}
	}
	
	function saveChanges(elt, resource, params, field)
	{		
		ns.api.request(resource, params, "POST", function(res) {
		    var $elt = $(elt);
            if(!res.ok)
            {
                var original_value = $elt.data("original-value"); 
                    
                $elt.text(original_value);
                changeDeeplink($elt, original_value);
                $elt.click();
                $elt.find("form").submit();
            }
            else
            {
                $elt.data("original-value", params[field]);
            }
		});		
	}
		
	function changeDeeplink(elt, value)
	{
	    var $dp = $(elt).parents("li").find(".dp"),
            href = $dp.attr("href"),
            aryPath = href.split("/");
        
        aryPath[aryPath.length - 1] = ns.forms.utils.gen_id_from_label(value);
        $dp.attr("href", aryPath.join("/"));
	}
		
})($.phui.yss);