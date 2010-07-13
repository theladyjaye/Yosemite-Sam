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