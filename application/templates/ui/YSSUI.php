<?php

/* UI Components */

class YSSUI 
{
	public static function BTN_ADD_PROJECT($url="#", $classes="", $label="Add Project") 
	{
		echo YSSUI::btn($url, $classes . " add btn-modal modal-view-add-project", $label, "icon-folder-add.png");
	}
	
	public static function BTN_ADD_VIEW($url="#", $classes="", $label="Add View") 
	{
		echo YSSUI::btn($url, $classes . " add btn-modal modal-view-add-view", $label, "icon-view-add.png");
	}
	
	public static function BTN_ADD_STATE($url="#", $classes="", $label="Add State") 
	{
		echo YSSUI::btn($url, $classes . " add-state btn-modal modal-view-add-state", $label, "icon-states-add.png");
	}
		
	public static function BTN_SUBMIT($url="#", $classes="", $label="SUBMIT") 
	{
		echo YSSUI::btn($url, $classes . " frm-btn-submit", $label, "icon-add-small.png");
	}	
		
	public static function BTN_ADD_USER($url="", $classes="", $label="Add User") 
	{
		echo YSSUI::btn($url, $classes . " add btn-modal modal-view-add-user", $label, "icon-add.png");
	}
	
	public static function BTN_EDIT($url="", $classes="", $label="Edit") 
	{
		echo YSSUI::btn($url, $classes . " edit", $label, "icon-edit.png");
	}
	
	public static function BTN_DELETE($url="", $classes="", $label="Delete") 
	{
		echo YSSUI::btn($url, $classes . " delete btn-modal modal-view-delete", $label, "icon-delete.png");
	}
	
	public static function BTN_CLOSE($url="#", $classes="", $label="Close") 
	{
		echo YSSUI::btn($url, $classes . " modal-close", $label, "icon-close.png");
	}
	
	private static function btn($url="#", $classes="", $label="Label", $icon=null)
	{
		$btn = '<a href="'. $url . '" class="'. $classes . ' font-replace btn">';
		if(isset($icon))
		{
			$btn .= '<img src="/resources/img/'. $icon . '" alt="'. $label . '" />';
		}
		
		$btn .= $label . '</a>';
		
		return $btn;
	}
	
	
}

?>