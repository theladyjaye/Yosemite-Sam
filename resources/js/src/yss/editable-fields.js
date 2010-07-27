(function(ns) 
{
	ns.editablefields = 
	{
		main: function() 
		{
			$(".editable").editable(saveChanges, {
		        indicator: "Saving...",
		        tooltip: "Click to edit...",
				onblur: "ignore",
		      	submit: 'Save',
		      	cancel: 'Cancel',
				name: 'new_value',				
				submitdata: {record: $.address.path()},
				callback: function(value, settings) {
					//console.log(this, value, settings);
				},
				cssclass: 'frm-editable',
				width: 'none',
				height: 'none'
			});

			$(".editable-textarea").editable(saveChanges, {
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
				callback: function(value, settings) {
					console.log(this, value, settings);
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
				callback: function(value, settings) {
					console.log(this, value, settings);
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
	
	function saveChanges(value, settings)
	{		
		ns.api.request(ns.utils.getItemPath($(this)), {label: value}, "POST", function(res) {
			console.log('res: ', res);
		});		
	}
		
})($.phui.yss);