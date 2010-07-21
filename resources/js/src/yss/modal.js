// all modals are triggered with buttons containing the class btn-modal
// the button must also contain the modal-view it is to show.
// Example: <a href="#" class="btn-modal modal-view-mywindow">popup modal</a>
//			<div class="modal modal-view-mywindow"></div>
// 			Once clicked, this button will show the modal => modal-view-mywindow

// Important: we are overriding the native alert(), and will display the modal in its place

(function(ns) {
	ns.modal = 
	{
		main: function() 
		{
			$(".btn-modal").click(function() {
				alert(ns.modal.get_modal_view(this));
				return false;
			});	

			$(".modal").addClass("jqmWindow").jqm({
				trigger: false,
				closeClass: "modal-close"
			});

			$(".modal form").submit(function() {
				return false;
			});
		},
		get_modal_view: function(btn)
		{
			var regExp = /modal-view-(\w|\d|-)*/;
			var class_names = $(btn)[0].className;
			var matches = class_names.match(regExp);
			return (matches.length) ? "." + matches[0] : "";
		}
	};		
})($.phui.yss);

/* -- Overrides -- */
function alert(view) 
{
	$(".modal" + view).jqmShow();
}
