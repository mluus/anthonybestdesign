<?php
/**
 * Plugin Name:       SharePrints Gallery
 * Plugin URI:        http://freakplugins.com/shareprints
 * Description:       SharePrints is a total gallery solution for WordPress that makes it easy for anyone to create beautiful responsive galleries that display perfectly across all devices and screen sizes.
 * Version:           1.0.4.5
 * Author:            JR w/Freak Plugins
 * Author URI:        http://freakplugins.com
 * Text Domain:       shareprints
 * License:           GPLv3+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path:       /languages
 * GitHub Plugin URI: 
 *
 * @package   Shareprints
 * @author    JR w/Freak Plugins <jr@freakplugins.com>
 * @license   GPLv3+
 * @link      http://freakplugins.com
 * @copyright Copyright (c) 2014 Freak Plugins, LLC - All Rights Reserved
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Conflict Resolution 
 *----------------------------------------------------------------------------*/
// Prevents Next-Gen from messing with the scripts in the footer. 
if ( ! defined( 'NGG_DISABLE_RESOURCE_MANAGER' ) ) {
	define('NGG_DISABLE_RESOURCE_MANAGER', true);
}




/*----------------------------------------------------------------------------*
 * Setup Shareprints Constants 
 *----------------------------------------------------------------------------*/

// Plugin version
if ( ! defined( 'SHAREPRINTS_VERSION' ) ) {
	define( 'SHAREPRINTS_VERSION', '1.0.4.5' );
}

// Plugin Folder Path
if ( ! defined( 'SHAREPRINTS_PLUGIN_DIR' ) ) {
	define( 'SHAREPRINTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL
if ( ! defined( 'SHAREPRINTS_PLUGIN_URL' ) ) {
	define( 'SHAREPRINTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File
if ( ! defined( 'SHAREPRINTS_PLUGIN_FILE' ) ) {
	define( 'SHAREPRINTS_PLUGIN_FILE', __FILE__ );
}




/*----------------------------------------------------------------------------*
 * Include Dependencies & set their constants
 *----------------------------------------------------------------------------*/
/**
 *
 * EDD updater
 *
 */
define( 'FREAKPLUGINS_STORE_URL', 'http://freakplugins.com' ); 
define( 'FREAKPLUGINS_ITEM_NAME', 'Shareprints Gallery' ); 
if( !class_exists( 'Shareprints_Updater' ) ) {
	include_once( SHAREPRINTS_PLUGIN_DIR . 'includes/shareprints_updater.php' );
}




/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
/**
 *
 * Main Plugin Class
 *
 */
require_once( SHAREPRINTS_PLUGIN_DIR . 'public/class-shareprints.php' );

/**
 *
 * Activation / Deactivation Hooks
 *
 */
register_activation_hook( __FILE__, array( 'Shareprints', 'shareprints_activation' ) );

/**
 *
 * Init the main class
 *
 */
add_action( 'plugins_loaded', array( 'Shareprints', 'get_instance' ) );




/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/
/**
 *
 * Load admin class
 *
 */
if ( is_admin() ) {

	require_once( SHAREPRINTS_PLUGIN_DIR . 'admin/class-shareprints-admin.php' );

	add_action( 'plugins_loaded', array( 'Shareprints_Admin', 'get_instance' ) );

}