<div class="modal modal-view-delete">
	<div class="modal-title font-replace">Delete</div>
	<?php YSSUI::BTN_CLOSE();?>
	
	<form name="frm-delete" action="#" method="post" class="modal-body">
		<a href="#" class="frm-delete-yes font-replace no_">Yes</a>
		<a href="#" class="frm-delete-no font-replace no_">No</a>
		
		<?foreach($modal["params"] as $key => $value):?>
			<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?endforeach;?>
	</form>
	
</div>

