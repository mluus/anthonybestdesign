<?php

function fsb_load_jquery() {
	global $fsb_pro_options;
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'backstretch', plugin_dir_url( __FILE__ ) . 'js/jquery.backstretch.min.js', array( 'jquery' ), '2.0.1' );
	wp_enqueue_script( 'fsb-scripts', plugin_dir_url( __FILE__ ) . 'js/front-end.js', array( 'jquery' ), '1.3' );
	wp_localize_script( 'fsb-scripts', 'fsb_vars', array(
			'fade_in' => isset( $fsb_pro_options['fadein_disable'] ) ? 'false' : 'true',
			'speed' => $fsb_pro_options['fadein_speed'] != '' ? $fsb_pro_options['fadein_speed'] : 800,
			'wait' => $fsb_pro_options['fadein_wait'] != '' ? $fsb_pro_options['fadein_wait'] : 1200
		)
	);
}
add_action( 'wp_enqueue_scripts', 'fsb_load_jquery' );

function fsb_pro_load_admin_scripts() {
	wp_enqueue_media();
	wp_enqueue_script( 'table-sorter', plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.min.js' );
	wp_enqueue_script( 'fsb-admin-scripts', plugin_dir_url( __FILE__ ) . 'js/admin-scripts.js' );
}


function fsb_pro_load_admin_styles() {
	wp_enqueue_style( 'fsb-admin', plugin_dir_url( __FILE__ ) . 'css/fsb-admin.css' );
	wp_enqueue_style( 'thickbox' );
}

if ( strstr( $_SERVER['REQUEST_URI'], 'wp-admin/themes.php?page=full-screen-background' ) ) {
	add_action( 'admin_print_scripts', 'fsb_pro_load_admin_scripts' );
	add_action( 'admin_print_styles', 'fsb_pro_load_admin_styles' );
}

function fsb_load_editor_scripts() {
	wp_enqueue_script( 'fsb-editorscripts', plugin_dir_url( __FILE__ ) . 'js/post-editor-scripts.js' );
}
add_action( 'admin_print_scripts-post.php', 'fsb_pro_load_admin_scripts' );
add_action( 'admin_print_scripts-post-new.php', 'fsb_pro_load_admin_scripts' );
