<?php
/*
Plugin Name: Full Screen Background Images Pro
Description: Easily set an automatically scaled full-screen background image
Plugin URI: http://pippinsplugins.com/advanced-full-screen-background-image-plugin
Version: 1.4.3
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
*/

/*****************************************
* global
*****************************************/

global $wpdb, $fsb_db_name, $fsb_db_version;

$fsb_db_name     = $wpdb->prefix . "fsb_images";
$fsb_db_version  = 1.1;
$fsb_prefix      = 'fsb_';
$fsb_pro_options = get_option( 'fsb_pro_settings' );

define( 'FSB_VERSION', '1.4.3' );
define( 'FSB_PLUGIN_FILE', __FILE__ );

function fsb_load_textdomain() {
	load_plugin_textdomain( 'fsb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'fsb_load_textdomain' );


/*****************************************
* includes
*****************************************/

include dirname( __FILE__ ) . '/includes/scripts.php';
include dirname( __FILE__ ) . '/includes/misc-functions.php';
if ( is_admin() ) {
	include dirname( __FILE__ ) . '/includes/admin-page.php';
	include dirname( __FILE__ ) . '/includes/process-images.php';
	include dirname( __FILE__ ) . '/includes/meta-box.php';
	include dirname( __FILE__ ) . '/includes/help-tab.php';
	include dirname( __FILE__ ) . '/includes/licensing.php';
} else {
	// is not admin
	include dirname( __FILE__ ) . '/includes/display-image.php';
}

// function to create the DB / Options / Defaults
function fsb_options_install() {
	global $wpdb;
	global $fsb_db_name;
	global $fsb_db_version;

	// create the ECPT metabox database table
	if ( $wpdb->get_var( "show tables like '$fsb_db_name'" ) != $fsb_db_name ) {
		$sql = "CREATE TABLE " . $fsb_db_name . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		url mediumtext NOT NULL,
		name tinytext NOT NULL,
		context tinytext NOT NULL,
		page_ids tinytext NOT NULL,
		needs_updated tinyint NOT NULL,
		parent_post mediumint NOT NULL,
		UNIQUE KEY id (id)
		);";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		@dbDelta( $sql );

		add_option( "fsb_db_version", $fsb_db_version );
	}

	if ( !$wpdb->query( "SELECT `slug` FROM `" . $fsb_db_name . "`" ) ) {
		$wpdb->query( "ALTER TABLE `" . $fsb_db_name . "` ADD `needs_updated` tinyint" );
		$wpdb->query( "ALTER TABLE `" . $fsb_db_name . "` ADD `parent_post` tinyint" );
	}
	// check if the meatbox fields table needs to be upgraded
	if ( get_option( 'fsb_db_version' ) == 1.0 ) {
		$wpdb->query( "ALTER TABLE " . $fsb_db_name . " MODIFY `parent_post` mediumint" );
		update_option( 'fsb_db_version', $fsb_db_version );
	}
}
register_activation_hook( __FILE__, 'fsb_options_install' );

// set up the FSB image sizes
function fsb_image_sizes() {
	add_image_size( 'fsb-preview',  400, 400, false ); // image preview size
	add_image_size( 'fsb-thumb',  150, 150, true ); // image thumbnail size
}
add_action( 'init', 'fsb_image_sizes' );

// add menu links to the plugin entry in the plugins menu
function fsb_action_links( $links, $file ) {
	static $this_plugin;

	if ( !$this_plugin ) {
		$this_plugin = plugin_basename( __FILE__ );
	}

	// check to make sure we are on the correct plugin
	if ( $file == $this_plugin ) {

		$fsb_links[] = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/themes.php?page=full-screen-background">' . __( 'Full Screen Background Settings', 'fsb' ) . '</a>';

		// add the links to the list of links already there
		foreach ( $fsb_links as $fsb_link ) {
			array_unshift( $links, $fsb_link );
		}
	}
	return $links;
}
add_filter( 'plugin_action_links', 'fsb_action_links', 10, 2 );
