(function(ns) 
{
	ns.editablefields = 
	{
		main: function() 
		{
			$(".editable").editable( "save.php", {
		        indicator: "Saving...",
		        tooltip: "Click to edit...",
				onblur: "ignore",
		      	submit: 'Save',
		      	cancel: 'Cancel',
				name: 'new_value',				
				submitdata: {record: $.address.path()},
				callback: function(value, settings) {
					console.log(this, value, settings);
				},
				width: 520
			});

			$(".editable-textarea").editable("save.php", {
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
				width: 520,
				height: 200
			});
		}
	}	
})($.phui.yss);