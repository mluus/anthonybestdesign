<?php

define( 'FSB_STORE_API_URL', 'http://pippinsplugins.com' );
define( 'FSB_PRODUCT_NAME', 'Full Screen Background Images Pro' );


function fsb_activate_license() {

  if ( ! isset( $_POST['fsb_pro_settings'] ) )
    return;
  if ( ! isset( $_POST['fsb_pro_settings']['license'] ) )
    return;

  if ( get_option( 'fsb_license_key_active' ) == 'valid' )
    return;

  $license = sanitize_text_field( $_POST['fsb_pro_settings']['license'] );

  // data to send in our API request
  $api_params = array(
    'edd_action'=> 'activate_license',
    'license'   => $license,
    'item_name' => urlencode( FSB_PRODUCT_NAME ), // the name of our product in EDD
    'url'       => home_url()
  );

  // Call the custom API.

  $response = wp_remote_get( add_query_arg( $api_params, FSB_STORE_API_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

  // make sure the response came back okay
  if ( is_wp_error( $response ) )
    return false;

  // decode the license data
  $license_data = json_decode( wp_remote_retrieve_body( $response ) );

  update_option( 'fsb_license_key_active', $license_data->license );

}
add_action( 'admin_init', 'fsb_activate_license' );


function fsb_deactivate_license() {

  global $fsb_pro_options;

  // listen for our activate button to be clicked
  if( isset( $_POST['fsb_license_deactivate'] ) ) {

    // run a quick security check
    if( ! check_admin_referer( 'fsb_license_nonce', 'fsb_license_nonce' ) )
      return; // get out if we didn't click the Activate button

    // retrieve the license from the database
    $license = trim( $fsb_pro_options['license'] );


    // data to send in our API request
    $api_params = array(
      'edd_action'=> 'deactivate_license',
      'license'   => $license,
      'item_name' => urlencode( FSB_PRODUCT_NAME ), // the name of our product in EDD
      'url'       => home_url()
    );

    // Call the custom API.
    $response = wp_remote_get( add_query_arg( $api_params, FSB_STORE_API_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

    // make sure the response came back okay
    if ( is_wp_error( $response ) )
      return false;

    // decode the license data
    $license_data = json_decode( wp_remote_retrieve_body( $response ) );

    // $license_data->license will be either "deactivated" or "failed"
    if( $license_data->license == 'deactivated' )
      delete_option( 'fsb_license_key_active' );

  }
}
add_action('admin_init', 'fsb_deactivate_license');


function fsb_updater() {

  if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include dirname( FSB_PLUGIN_FILE ) . '/EDD_SL_Plugin_Updater.php';
  }

  $options = get_option( 'fsb_pro_settings' );

  // retrieve our license key from the DB
  $fsb_license_key = isset( $options['license'] ) ? trim( $options['license'] ) : '';

  if( empty( $fsb_license_key ))
    return;

  // setup the updater
  $edd_stripe_updater = new EDD_SL_Plugin_Updater( FSB_STORE_API_URL, __FILE__, array(
      'version'   => FSB_VERSION,   // current version number
      'license'   => $fsb_license_key, // license key (used get_option above to retrieve from DB)
      'item_name' => FSB_PRODUCT_NAME, // name of this plugin
      'author'    => 'Pippin Williamson'  // author of this plugin
    )
  );

}
add_action( 'admin_init', 'fsb_updater' );