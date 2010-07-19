<div id="global-body">
	<div id="view-table-header" class="view-header">
		<div class="page-title icon-view font-replace">			
			Views <span class="count">(<?=count($data)?>)</span>		
		</div>
		<?php YSSUI::BTN_ADD_VIEW(); ?>
	</div>
	
	<div id="view-body">
		<ul id="view-table-list">
			<?php foreach($data as $i => $item): ?>			
			<li class="<?if($i % 2):?>alt<?endif;?>">				
				<a href="editor.php" title="" class="item-thumb no_">
					
					<?if(count($item["attachments"]) > 0):
						for($i = 0; $i < count($item["attachments"]) && $i < 5; $i++):?>
							<img src="resources/img/icon-image.png" alt="Image" class="item-thumb-stack-<?=$i+1?>" />
						<?endfor;
					  else:?>
						<img src="resources/img/icon-no-image.png" alt="Image" class="item-thumb-stack-1" />
					<?endif;?>
				</a>
				<a href="editor.php" title="" class="item-name field-label"><?=$item["item-name"]?></a>
				<a href="#" class="item-details font-replace">details</a>
				<div class="progress-bar">
					<div class="progress-value font-replace">
						<span class="value"><?=$item["percentage"]?></span>
						<span class="percentage-sign">%</span>
					</div>
				</div>
				<?php YSSUI::BTN_EDIT("#");?>
				<?php YSSUI::BTN_DELETE("#");?>
				
				<div class="item-detail">
					<p class="item-tasks icon-check">Tasks <span class="tasks-closed"><?=$item["tasks-closed"]?></span>/<span class="tasks-total"><?=$item["tasks-total"]?></span></p>					
					<p class="item-attachments icon-attachment">Attachments <span class="attachment-count"><?=count($item["attachments"])?></span>
						<select class="dd-attachments" <?if(count($item["attachments"]) <= 0):?>disabled="true"<?endif;?>>
							<?foreach($item["attachments"] as $attachment_dex => $attachment):?>
								<option value="<?=$attachment_dex?>"><?=$attachment?></option>
							<?endforeach;?>
						</select>
						<a href="#" class="add-attachment icon-attachment-add no_ btn-modal modal-view-add-attachment">Add Attachment</a>
					</p>					
					<div class="item-states-container">
						<?php include("body-states.php"); ?>
					</div>										
					<div class="item-desc field-description">
						<?=$item["desc"]?>
					</div>
				</div>
			</li>
			<?endforeach;?>			
		</ul>
	</div>
</div>