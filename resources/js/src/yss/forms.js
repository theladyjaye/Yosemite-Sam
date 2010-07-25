(function(ns) {
	ns.forms = 
	{
		main: function()
		{
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
					id = ns.forms.utils.gen_id_from_label(label),
				 	serialized = ns.forms.utils.serialize($this),
					query_string = serialized + "&id=" + id;

				var params = {};
				query_string.replace(/([^=&]+)=([^&]*)/g, function(match, key, value) {
					params[unescape(key)] = value.replace(/(\+)/g, " ");
				});

				if(ns.forms.add_project($this)) {
					ns.api.request("/project/" + id, params, "PUT", function(data) {
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
		},
		add_project: function($frm) 
		{
			var label = $frm.find("input[name=label]").val();
	//		 	description = $frm.find("textarea[name=description]").val();

			return ns.forms.validation.regexp.label.test(label);		
		},
		utils:
		{
			serialize: function($frm)
			{
				var serialized = $frm.serialize();

				for(var i in serialized)
				{
					serialized[i] = $.trim(serialized[i]);
				}

				return serialized;
			},
			gen_id_from_label: function(label)
			{
				// replace underscores and spaces to hypens
				return label.replace(/(_|\s)/g, "-").toLowerCase();
			}
		}
	}
})($.phui.yss)