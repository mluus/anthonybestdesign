<?php

function fsb_admin_page() {

	global $wpdb;
	global $fsb_options;
	global $fsb_db_name;

	// check for which admin page we're accessing first
	if(isset($_GET['action'])) {
		if($_GET['action'] == 'add_new_image') {
			include('add-new-image.php');
		} else if ($_GET['action'] == 'edit_image') {
			include('edit-image.php');
		} else if ($_GET['action'] == 'delete_image') {
			include('delete-image.php');
		}
	} else {
		include('image-list.php');
	}
}

function fsb_init_admin() {
	global $fsb_admin;
	$fsb_admin = add_submenu_page( 'themes.php', __('Full Screen Background Image', 'fsb'), __('Fullscreen BG Image', 'fsb'), 'manage_options', 'full-screen-background', 'fsb_admin_page' );
	if ( get_bloginfo('version') >= 3.3 ) {
		add_action('load-' . $fsb_admin, 'fsb_help_tabs');
	}
}
add_action('admin_menu', 'fsb_init_admin', 10);

// register the plugin settings
function fsb_pro_register_settings() {
	register_setting( 'fsb_pro_register_settings', 'fsb_pro_settings', 'fsb_validate_settings' );
}
add_action( 'admin_init', 'fsb_pro_register_settings' );

// validate the input values
function fsb_validate_settings($input) {

	if( ! isset( $input['fadein'] ) || ! is_numeric( $input['fadein'] ) ) {
		$input['fadein'] = 800;
	}
	if( isset( $input['license'] ) ) {
		$input['license'] = sanitize_text_field( $input['license'] );
	}
	return $input;
}