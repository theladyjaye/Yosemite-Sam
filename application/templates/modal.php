<div class="modal modal-view-<?=$modal["id"]?>">
	<div class="modal-title font-replace"><?=$modal["title"]?></div>
	<div class="modal-error"><img src="resources/img/icon-warning.png" />Invalid Email</div>
	<?php YSSUI::BTN_CLOSE();?>
	
	<form name="frm-<?=$modal["id"]?>" action="<?=$modal["action"]?>" method="post" class="modal-body">
		<?=$modal["body"]?>
		<?php YSSUI::BTN_SUBMIT("#", "", $modal['submit-label']);?>
	</form>
	
</div>

