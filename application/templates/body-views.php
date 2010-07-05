<div id="global-body">
	<div id="view-table-header" class="view-header">
		<div class="page-title icon-view font-replace">			
			Views <span class="count">(7)</span>		
		</div>
		<a href="#" title="Add View" class="font-replace add icon-add no_ btn-modal modal-view-add-view">Add View</a>
	</div>
	<div id="view-editor-header" class="view-header">
	</div>
	
	<div id="view-body">
		<ul id="view-table-list">
			<?php foreach($data as $i => $item): ?>			
			<li class="<?if($i % 2):?>alt<?endif;?>">
				<a href="#" title="" class="item-name font-replace"><?=$item["item-name"]?></a>
				<a href="#" class="item-details font-replace">details</a>
				<div class="progress-bar">
					<div class="progress-value font-replace">
						<span class="value"><?=$item["percentage"]?></span>
						<span class="percentage-sign">%</span>
					</div>
				</div>
				<a href="#" class="delete icon-delete no_ font-replace btn-modal modal-view-delete">Delete</a>
				
				<div class="item-detail">
					<p class="item-tasks icon-check">Tasks <span class="tasks-closed"><?=$item["tasks-closed"]?></span>/<span class="tasks-total"><?=$item["tasks-total"]?></span></p>					
					<p class="item-states icon-states">States <span class="view-count"><?=count($item["states"])?></span>
						(<?foreach($item["states"] as $state_dex => $state):?>
							<a href="#"><?=$state?></a><?if($state_dex < count($item["states"]) - 1):?>,<?endif;?>
						<?endforeach;?>)						
					</p>
					<p class="item-attachments">Attachments <span class="attachment-count"><?=count($item["attachments"])?></span>
						<select class="dd-attachments">
							<?foreach($item["attachments"] as $attachment_dex => $attachment):?>
								<option value="<?=$attachment_dex?>"><?=$attachment?></option>
							<?endforeach;?>
						</select>
						<a href="#" class="add-attachment icon-add-small no_">Add Attachment</a>
					</p>					
					<div class="item-desc">
						<?=$item["desc"]?>
					</div>
				</div>
			</li>
			<?endforeach;?>			
		</ul>
	</div>
</div>