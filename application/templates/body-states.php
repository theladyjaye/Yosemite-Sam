<p class="item-states icon-states">States <span class="view-count"><?=count($item["states"])?></span></p>
<a href="#" class="add-state icon-states-add no_ btn-modal modal-view-add-state">Add State</a>
<ul>
	<?php foreach($item["states"] as $i => $state_item): ?>			
	<li class="<?if($i % 2):?>alt<?endif;?>">
		<a href="editor.php" title="" class="item-name font-replace"><?=$state_item["item-name"]?></a>
		<p class="item-tasks icon-check">Tasks <span class="tasks-closed"><?=$state_item["tasks-closed"]?></span>/<span class="tasks-total"><?=$state_item["tasks-total"]?></span></p>
		<div class="progress-bar">
			<div class="progress-value font-replace">
				<span class="value"><?=$state_item["percentage"]?></span>
				<span class="percentage-sign">%</span>
			</div>
		</div>
		<?php YSSUI::BTN_EDIT("#");?>
		<?if(count($item["states"]) > 1):?>
		<?php YSSUI::BTN_DELETE("#");?>
		<?endif;?>
	</li>
	<?endforeach;?>			
</ul>