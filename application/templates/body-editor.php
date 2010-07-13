<div id="global-body">
	<div id="view-table-header" class="view-header">
		<div class="task-info">
			<div class="progress-bar">
				<div class="progress-value font-replace">
					<span class="value"><?=$data["percentage"]?></span>
					<span class="percentage-sign">%</span>
				</div>
			</div>
			
			<p class="item-tasks icon-check">Tasks <span class="tasks-closed"><?=$data["tasks-closed"]?></span>/<span class="tasks-total"><?=$data["tasks-total"]?></span></p>
			<a href="#" class="item-details font-replace">details</a>
			
		</div>
		<div class="attachment-list">
			<p class="font-replace">Attachments</p>
			<select id="dd-attachments">
				<option value="34949">Buzz</option>
				<option value="34950">Games</option>
				<option value="34951">Home</option>
				<option value="34951a">-- Logged in</option>
				<option value="34951b">-- Logged Out</option>
				<option value="34952">Learn</option>
				<option value="34953">Media</option>
				<option value="34954">Music</option>
				<option value="34955">The Stage</option>
			</select>
		</div>		
		<a href="#" title="Add Attachment" class="font-replace add icon-attachment-add no_ btn-modal modal-view-add-attachment">Add Attachment</a>
	</div>	
</div>