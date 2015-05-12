<?php

function fsb_help_tabs_old($contextual_help, $screen_id, $screen) {
	
	global $fsb_admin;
	
	// replace edit with the base of the page you're adding the help info to
 	
	switch($screen->base) :
	
		case $fsb_admin:
			$contextual_help = '<h3>' . __('Purpose of the Plugin', 'fsb') . '</h3>';
			$contextual_help .= fsb_render_help_tab_content('purpose');
			
			$contextual_help .= '<h3>' . __('How to Add New Images', 'fsb') . '</h3>';
			$contextual_help .= fsb_render_help_tab_content('adding_images');
			
			$contextual_help .= '<h3>' . __('Editing an Image', 'fsb') . '</h3>';
			$contextual_help .= fsb_render_help_tab_content('editing_images');
			
			$contextual_help .= '<h3>' . __('Removing an Image', 'fsb') . '</h3>';
			$contextual_help .= fsb_render_help_tab_content('removing_images');
			
			return $contextual_help;
		break;	
		
		default:
			// show the default WP help tab content
			return $contextual_help;
		break;
	endswitch;

}
add_action('contextual_help', 'fsb_help_tabs_old', 100, 3);