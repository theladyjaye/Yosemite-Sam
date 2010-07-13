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
		<a href="#" class="edit icon-edit no_ font-replace">Edit</a>
		<?if(count($item["states"]) > 1):?>
		<a href="#" class="delete icon-delete no_ font-replace btn-modal modal-view-delete">Delete</a>
		<?endif;?>
	</li>
	<?endforeach;?>			
</ul>