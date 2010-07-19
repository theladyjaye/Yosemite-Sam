$(function() {
	$(".editable").editable( "save.php", {
        indicator: "Saving...",
        tooltip: "Click to edit..."
	});

	$(".editable-area").editable("save.php", {
        type: "textarea",
        cancel: "Cancel",
        submit: "Update",
        tooltip: "Click to edit...",
        data: function(value, settings) {
        	return value.replace(/<br[\s\/]?>/gi, "\n");
        }
	});
});