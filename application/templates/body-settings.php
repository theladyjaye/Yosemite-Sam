<div id="global-body">
	<div id="view-table-header" class="view-header">
		<div class="page-title icon-settings font-replace">
			Settings <? /*<span class="count">(5)</span> */ ?>
		</div>
		<?php YSSUI::BTN_ADD_USER(); ?>
	</div>
	
	<div id="view-body">
		<ul id="view-table-list" class="settings">			
			<?php foreach($data as $i => $item): ?>			
			<li class="<?if($i % 2):?>alt<?endif;?> user-level-<?=$item["user-level"]?> <?if($logged_in_user == $item["username"]):?>logged-in-user<?endif;?>">
				<p class="item-name font-replace"><?=$item["last-name"]?>, <?=$item["first-name"]?></p>
				<a href="#" class="item-username font-replace"><?=$item["username"]?></a>
				<a href="mailto:<?=$item["email"]?>" class="item-email font-replace"><?=$item["email"]?></a>								
				<p class="item-user-level font-replace"><?=$item["user-level"]?></p>
				<?php YSSUI::BTN_EDIT(); ?>
				<?php YSSUI::BTN_DELETE(); ?>
			</li>
			<?endforeach;?>			
		</ul>
	</div>
</div>