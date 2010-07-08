<div id="global-body">
	<div id="view-table-header" class="view-header">
		<div class="page-title icon-folder font-replace">
			Projects <span class="count">(5)</span> 
		</div>
		<a href="#" title="Add Project" class="font-replace add icon-folder-add no_ btn-modal modal-view-add-project">Add Project</a>
	</div>
	
	<div id="view-body">
		<ul id="view-table-list">
			<?php foreach($data as $i => $item): ?>			
			<li class="<?if($i % 2):?>alt<?endif;?>">
				<a href="views.php" title="" class="item-name font-replace"><?=$item["item-name"]?></a>
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
					<p class="item-views icon-view-small">Views <span class="view-count"><?=$item["views-count"]?></span></p>
					<div class="item-desc">
						<?=$item["desc"]?>
					</div>
				</div>
			</li>
			<?endforeach;?>			
		</ul>
	</div>
</div>