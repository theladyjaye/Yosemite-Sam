<div class="modal modal-view-<?=$modal["id"]?>">
	<div class="modal-title font-replace"><?=$modal["title"]?></div>
	<div class="modal-error"><img src="resources/img/icon-warning.png" />Invalid Email</div>
	<a href="#" class="font-replace modal-close icon-close no_">Close</a>
	
	<form name="frm-<?=$modal["id"]?>" action="<?=$modal["action"]?>" method="post" class="modal-body">
		<?=$modal["body"]?>
		<input type="submit" class="frm-btn-submit icon-add-small font-replace" value="<?=$modal["submit-label"]?>" />		
	</form>
	
</div>

