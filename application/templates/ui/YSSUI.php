<?php

/* UI Components */

class YSSUI 
{
	public static function BTN_EDIT($url="#", $classes="", $label="Edit") 
	{
		echo <<<UI
			<a href="$url" class="$classes edit font-replace btn">
				<img src="/resources/img/icon-edit.png" alt="Edit" />
				$label
			</a>
UI;
	}
	
	public static function BTN_DELETE($url="#", $classes="", $label="Delete") 
	{
		echo <<<UI
			<a href="$url" class="$classes delete font-replace btn-modal modal-view-delete btn">
				<img src="/resources/img/icon-delete.png" alt="Delete" />
				$label
			</a>
UI;
	}
	
	
	
}

?>