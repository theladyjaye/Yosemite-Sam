(function(ns) {
	ns.tasklist =
	{
		main: function() 
		{
			$(".task-list-container .btn-close-task-list").click(function() {
				$(".task-list-container").fadeOut();
			});
		}
	}
})($.phui.yss);