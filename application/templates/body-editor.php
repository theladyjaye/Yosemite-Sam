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
		<a href="#" title="Add Attachment" class="font-replace add icon-add no_ btn-modal modal-view-add-attachment">Add Attachment</a>
		
		<div class="task-list-container">
			<div class="task-list">
				<div class="sort-tasks">
					<span class="font-replace">Sort by</span> 
					<select id="dd-sort-tasks">
						<option value="username">username</option>
						<option value="type">type</option>
					</select>
					<a href="#" class="font-replace icon-close no_ btn-close-task-list">Close</a>
				</div>				
				<div class="task-group">
					<p class="task-title font-replace">Open Tasks <span class="tasks-open">32</span></p>
					<ol>
						<?for($i = 1; $i <= 10; $i++):?>
						<li class="<?if($i % 2):?>alt<?endif;?>">
							<p class="task-count"><?=$i?>.</p>
							<div class="task-info-group">
								<p class="task-desc">Playlist will be AJAX.  It will read from provided web services</p>
								<p class="task-type">HTML</p>
								<p class="task-assignee">bross</p>
							</div>
							<div class="task-status-group">
								<a href="#" title="" class="task-status no_ close-task">Close Task</a>
								<a href="#" title="" class="task-delete icon-delete delete no_">Delete</a>
							</div>
						</li>
						<?endfor;?>
					</ol>
				</div>
				<div class="task-group">
					<p class="task-title font-replace">Closed Tasks <span class="tasks-closed">26</span></p>
					<ol>
						<?for($i = 1; $i <= 10; $i++):?>
						<li class="<?if($i % 2):?>alt<?endif;?>">
							<p class="task-count"><?=$i?>.</p>
							<div class="task-info-group">
								<p class="task-desc">Playlist will be AJAX.  It will read from provided web services</p>
								<p class="task-type">HTML</p>
								<p class="task-assignee">bross</p>
							</div>
							<div class="task-status-group">
								<a href="#" title="" class="task-status no_ reopen">Reopen</a>
								<a href="#" title="" class="task-delete icon-delete delete no_">Delete</a>
							</div>
						</li>
						<?endfor;?>
					</ol>
				</div>
			</div>
		</div>
	</div>	
</div>