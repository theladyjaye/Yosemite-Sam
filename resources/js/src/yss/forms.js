$(function() {
	$(".fakefile input").keypress(function() {
        $(this).val("");
        return false;
	});

	$(".frm-btn-submit").click(function() {
        $(this).parents("form").submit();
        return false;
	});
	
	$("form[name=frm-add-project]").submit(function() {
		var $this = $(this), 
			label = $this.find("input[name=label]").val(),
			id = gen_id_from_label(label),
		 	serialized = form_serialize($this),
			query_string = serialized + "&id=" + id;

		var params = {};
		query_string.replace(/([^=&]+)=([^&]*)/g, function(match, key, value) {
			params[unescape(key)] = value.replace(/(\+)/g, " ");
		});
		
		if(Validation.add_project($this)) {
			api("project/" + id, params, "PUT", function(data) {
				if(data.ok)
				{
//					$this.parents("modal").find(".modal-close").click();
					/*todo: make ajax */
					document.location.reload(false);
				}
				else
				{
					var errors = data.errors;
					var key, msg;
					for(var i in errors)
					{
						key = errors[i].key;
						msg = errors[i].message;						
						
						$this.find("label[for=" + key + "]").next(".error").text(msg).fadeIn();
					}					
				}
			});
		}
		return false;
	});
});

var Validation = {
	regexp: {
		label: /(\w|\d|_|-){2,}/ // at least 2 (word, digit, _, -)
	},
	add_project: function($frm) {
		var label = $frm.find("input[name=label]").val();
//		 	description = $frm.find("textarea[name=description]").val();

		return Validation.regexp.label.test(label);		
	}
};

function form_serialize($frm) 
{
	var serialized = $frm.serialize();
	
	for(var i in serialized)
	{
		serialized[i] = $.trim(serialized[i]);
	}
	
	return serialized;
}

function gen_id_from_label(label)
{
	// replace underscores and spaces to hypens
	return label.replace(/(_|\s)/g, "-").toLowerCase();
}