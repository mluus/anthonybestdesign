<?php

function fsb_help_tabs() {
	global $fsb_admin;
	
	$screen = get_current_screen();

	if(!is_object($screen) || $screen->id != $fsb_admin)
		return;
		

	$screen->add_help_tab(
		array(
			'id' => 'purpose',
			'title' => __('Purpose of the Plugin', 'fsb'),
			'content' => fsb_render_help_tab_content('purpose')
		)
	);
	$screen->add_help_tab(
		array(
			'id' => 'adding_images',
			'title' => __('Adding New Images', 'fsb'),
			'content' => fsb_render_help_tab_content('adding_images')
		)
	);
	$screen->add_help_tab(
		array(
			'id' => 'editing_images',
			'title' => __('Editing an Image', 'fsb'),
			'content' => fsb_render_help_tab_content('editing_images')
		)
	);
	$screen->add_help_tab(
		array(
			'id' => 'removing_images',
			'title' => __('Removing an Image', 'rcp'),
			'content' => fsb_render_help_tab_content('removing_images')
		)
	);

}
add_action('admin_menu', 'fsb_help_tabs', 100);