<?php
/**
 * Shareprints Gallery
 *
 * @package   Shareprints
 * @author    JR w/Freak Plugins <jr@freakplugins.com>
 * @license   GPLv3+
 * @link      http://freakplugins.com
 * @copyright Copyright (c) 2014 Freak Plugins, LLC - All Rights Reserved
 */
class Shareprints_Admin {

	/**
	 *
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 *
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

		$this->plugin = Shareprints::get_instance();
		$this->plugin_slug = $this->plugin->get_plugin_slug();

		// General Init
		add_action('admin_init', array( $this, 'init' ));

		// Load Scripts and Styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_styles' ) );

		// media buttons
		add_action('media_buttons', array( $this, 'add_shareprints_media_button'), 20);
		
		// mce modal template
		add_action('admin_footer',  array( $this, 'load_shareprints_mce_modal_template'));

		// tinyMCE plugins
		add_filter('mce_external_plugins', array( $this, 'shareprints_tinymce_plugins'));

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this , 'add_shareprints_settings_page' ) );

		// Add settings page link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( SHAREPRINTS_PLUGIN_FILE ) , array( $this , 'add_shareprints_settings_link' ) );
		
		// Media library ID column
		add_filter('manage_media_columns', array( $this, 'add_media_library_ID_column'));
		add_filter('manage_media_custom_column', array( $this, 'media_library_ID_column'), 10, 2 );
		add_filter('manage_upload_sortable_columns', array( $this, 'media_library_ID_column_sort'));

		// Attachment meta
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'shareprints_thumbs_generated_meta_field' ));
		
		// Preps images for use in the Media Library
		add_filter('wp_prepare_attachment_for_js', array($this, 'wp_prepare_attachment_for_js'), 10, 3);

		// AJAX 
		add_action('wp_ajax_return_shareprints_gallery', array( $this, 'return_shareprints_gallery')); 
		add_action('wp_ajax_delete_shareprints_gallery', array( $this, 'delete_shareprints_gallery'));
		add_action('wp_ajax_save_update_shareprints_gallery', array( $this, 'save_update_shareprints_gallery'));
		add_action('wp_ajax_save_mce_modal_settings', array( $this, 'save_mce_modal_settings'));
		add_action('wp_ajax_delete_mce_modal_settings', array( $this, 'delete_mce_modal_settings'));
		add_action('wp_ajax_shareprints_check_thumbs', array( $this, 'shareprints_check_thumbs'));
		
	}

	/**
	 *
	 * get_instance()
	 *
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 *
	 * init()
	 *
	 * General Init
	 *
	 * @since 1.0.0
	 */
	function init(){
		
		// Welcome
		$this->shareprints_welcome_redirect();
			
		// Automatic updates
		$this->shareprints_plugin_updater();
		
		// Plugin notices
		$this->shareprints_notices();

		// Localize shareprints scripts
		$this->localize_shareprints_scripts();

		//Tinymce styles
		$this->shareprints_tinymce_styles();
		
		//shortcode favorites
		add_option('shareprints_mce_favorites', array());
		
		// License key setting
		register_setting('shareprints_settings', 'shareprints_license_key', array( $this, 'shareprints_sanitize_license') );

	}


// Scripts and Styles
	/**
	 *
	 * enqueue_admin_scripts_and_styles()
	 *
	 * Enqueue scripts and styles
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin_scripts_and_styles() {

		$screen = get_current_screen();

		// Shareprints CPT edit screen
		//if($screen->post_type == $this->plugin_slug && $screen->base === 'post' ){}

		// Media Library edit screen
		if($screen->id === 'upload'){
		
			wp_enqueue_style('shareprints-media-library-style');
			
		}
		
		if($screen->id === 'shareprints_page_shareprints_settings'){
			
			wp_enqueue_style('shareprints-settings-page-style');
			
		}
		
		if($screen->post_type !== $this->plugin_slug && ($screen->base === 'post' || $screen->base === 'page')){
		
			wp_enqueue_style('shareprints-mce-modal-style');
			wp_enqueue_script('shareprints-bootstrap-js');
			wp_enqueue_script('shareprints-mce-modal-script');
			
		}

		if($screen->base === 'post' || $screen->base === 'page'){
			
			wp_enqueue_style('shareprints-images-style');

			wp_enqueue_script(array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-sortable',
				'thickbox',
				'media-upload',
			));

			wp_enqueue_script('shareprints-images-script');
			
			wp_enqueue_media();
			
		}
		
	}

	/**
	 *
	 * localize_shareprints_scripts()
	 *
	 * Localize Shareprints Scripts
	 *
	 * @since 1.0.0
	 */
	private function localize_shareprints_scripts(){
		
		$scripts = array(
			// Back End
			array(
				'handle' 		=> 'shareprints-images-script',
				'object_name' 	=> 'shareprints_images_l10n',
				'l10n' 			=> array( 
					'select'			=>	"SharePrints - ".__("Add Images",'shareprints'),
					'edit'				=>	"SharePrints - ".__("Edit Image",'shareprints'),
					'update'			=>	__("Update Image",'shareprints'),
					'uploadedTo'		=>	__("uploaded to this post",'shareprints'),
					
					'count_0'			=>	__("No images selected",'shareprints'),
					'count_1'			=>	__("1 image selected",'shareprints'),
					'count_2'			=>	__("%d images selected",'shareprints'),
					
					'expand_details'	=>	__("Expand Details",'shareprints'),
					'collapse_details'	=>	__("Collapse Details",'shareprints'),
					
					'url' 				=> admin_url( 'admin-ajax.php' ),
					'ajax_nonce' 		=> wp_create_nonce('check-for-thumbs'),
					'save_update_nonce' => wp_create_nonce( 'shareprints-gallery-images' ),
					
					'add_modal_title' 	=> "SharePrints - ".__("Add New Gallery",'shareprints'),
					'edit_modal_title' 	=> "SharePrints - ".__("Edit Gallery",'shareprints'),
					
					
					'ajax_error' 		=> __("Ajax Error",'shareprints'),
					'retry' 			=> __("Retry?",'shareprints'),
					'yes' 				=> __("Yes",'shareprints'),
					'no' 				=> __("No",'shareprints'),
					'image_na' 			=> __("Image needs your attention",'shareprints'),
					'images_na' 		=> __("Images need your attention",'shareprints'),
					'processing_images' => __("Processing Images",'shareprints'),
					'please_wait' 		=> __("Please wait for images to finish processing.",'shareprints'),
				
				)
			),

			array(
				'handle' 		=> 'shareprints-mce-modal-script',
				'object_name' 	=> 'shareprintsAjax',
				'l10n' 			=> array( 
					'url' 			=> admin_url( 'admin-ajax.php' ),
					'ajax_nonce' 	=> wp_create_nonce('shareprints-mce-modal-actions'),
					'assetpath' 	=> SHAREPRINTS_PLUGIN_URL . 'assets/',
			   )
			),

		);

		foreach( $scripts as $script ){
			wp_localize_script( $script['handle'], $script['object_name'], $script['l10n'] );
		}

	}

	/**
	 *
	 * shareprints_tinymce_styles()
	 *
	 * tinyMCE styles
	 *
	 * @since 1.0.0
	 */
	public function shareprints_tinymce_styles() {

	    add_editor_style( SHAREPRINTS_PLUGIN_URL . 'assets/tinymce/css/sp_tinymce.css' );

	}
	
	/**
	 *
	 * shareprints_tinymce_plugins()
	 *
	 * Adds our tinymce scripts to the editor
	 *
	 * @since 1.0.0
	 */
	public function shareprints_tinymce_plugins($plugin_array){
	     
		global $wp_version;
		
		if ( $wp_version < 3.9 ) {
	    
		    $plugin_array['shareprints_shortcode_editor'] =  SHAREPRINTS_PLUGIN_URL . 'assets/tinymce/editor_plugin_src_lt-3.9-jr.js';
		
		}else if ( $wp_version < 4.0 ){
			
		    $plugin_array['shareprints_shortcode_editor'] =  SHAREPRINTS_PLUGIN_URL . 'assets/tinymce/editor_plugin_src_lt-4.0-jr.js';
			
		}else{
			
		    $plugin_array['shareprints_shortcode_editor'] =  SHAREPRINTS_PLUGIN_URL . 'assets/tinymce/editor_plugin_src-jr.js';
			
		}
	     
	     return $plugin_array;

   	}


// Settings Page
	/**
	 *
	 * add_shareprints_settings_page()
	 *
	 * adds the settings submenu to the shareprints menu
	 *
	 * @since 1.0.0
	 */
	public function add_shareprints_settings_page() {
		
		add_submenu_page( 'edit.php?post_type=shareprints', __('SharePrints Settings','shareprints'), 'Settings', 'manage_options', 'shareprints_settings', array( $this, 'render_shareprints_settings_page' ) ); 
		//add_submenu_page( 		    $parent_slug,         $page_title,       	$menu_title,      $capability,        $menu_slug,        $function );

	}

	/**
	 *
	 * add_shareprints_settings_link()
	 *
	 * Add a link to the settings page from the plugins page
	 *
	 * @since 1.0.0
	 */
	public function add_shareprints_settings_link( $links ) {
 		$settings_link = '<a href="edit.php?post_type=shareprints&page=shareprints_settings" title="'.__('SharePrints Settings','shareprints').'">'.__('Settings','shareprints').'</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 *
	 * render_shareprints_settings_page()
	 *
	 * outputs the settings page
	 *
	 * @since 1.0.0
	 */
	public function render_shareprints_settings_page() {
		
		include_once( SHAREPRINTS_PLUGIN_DIR . 'admin/includes/settings_page.php' );
		
	}

	/**
	 *
	 * shareprints_welcome_redirect()
	 *
	 * redirects to welcome tab of settings page on activation
	 *
	 * @since 1.0.0
	 */
	public function shareprints_welcome_redirect() {

		// Bail if no activation redirect
		if( !get_transient('shareprints_activation_redirect') )
		return;

		// Delete the redirect transient
		delete_transient('shareprints_activation_redirect');

		// Bail if activating from network, or bulk
		if( is_network_admin() || isset($_GET['activate-multi']) )
		return;

		wp_safe_redirect( admin_url( 'edit.php?post_type=shareprints&page=shareprints_settings&tab=welcome' ) ); 
		exit;

	}


// Automatic updates
	/**
	 *
	 * shareprints_sanitize_license()
	 *
	 * callback for the license field on the settings page
	 *
	 * @since 1.0.0
	 */
	public function shareprints_sanitize_license( $license ) {

		$license = sanitize_key($license);
		
		return $license;
		
	}

	/**
	 *
	 * shareprints_plugin_updater()
	 *
	 * plugin updates
	 *
	 * @since 1.0.0
	 */
	private function shareprints_plugin_updater() {
		$l=get_option('shareprints_license_status');
		$s=trim(get_option('shareprints_license_key'));
		new Shareprints_Updater(FREAKPLUGINS_STORE_URL,SHAREPRINTS_PLUGIN_FILE,array('version'=>SHAREPRINTS_VERSION,'license'=>$s,'item_name'=>FREAKPLUGINS_ITEM_NAME,'author'=>'Freak Plugins'));
		if(($s&&$s!=='')&&!get_transient('shareprints_check_license')){
			$this->shareprints_update_license('check_license',$s);
			$l=get_option('shareprints_license_status');
			set_transient('shareprints_check_license',$l,2*WEEK_IN_SECONDS);}
		if(check_ajax_referer('shareprints-license-nonce','shareprints-license-nonce',false)&&$_POST&&isset($_POST['shareprints_license_key'])){
			$n = sanitize_key($_POST['shareprints_license_key']);
			if(($s&&$s!=='')&&(($s!==$n)||isset($_POST['shareprints_license_deactivate']))&&$l==='valid')
			$this->shareprints_update_license('deactivate_license',$s);
			if($n!==''&&($n!==$s||isset($_POST['shareprints_license_activate'])))
			$this->shareprints_update_license('activate_license',$n);
			if($n==='')
			update_option('shareprints_license_status','');}
	}

	/**
	 *
	 * shareprints_update_license()
	 *
	 * plugin updates
	 *
	 * @since 1.0.0
	 */
	private function shareprints_update_license($action, $license) {
	 	if($action!=='check_license'&&!check_admin_referer('shareprints-license-nonce','shareprints-license-nonce',false)) 	
		return;
		$api_params=array('edd_action'=>$action,'license'=>$license,'item_name'=>urlencode(FREAKPLUGINS_ITEM_NAME));
		$response=wp_remote_get(add_query_arg($api_params,FREAKPLUGINS_STORE_URL),array('timeout'=>15,'sslverify'=>false));
		if(is_wp_error($response))
		return;
		$response_data=json_decode(wp_remote_retrieve_body($response));
		if(!$response_data)
		return;
		update_option('shareprints_license_status',$response_data->license);
		set_transient('shareprints_check_license',$response_data->license,2*WEEK_IN_SECONDS);
	}

	/**
	 *
	 * shareprints_notices()
	 *
	 *
	 * @since 1.0.0
	 */
	function shareprints_notices() {
		
		$status = get_option( 'shareprints_license_status' );
		
		if($status !== 'valid'){
			add_action('admin_notices', array(&$this, 'license_notice'));
		}
		
		
	}

	/**
	 *
	 * license_notice()
	 *
	 *
	 * @since 1.0.0
	 */
	function license_notice() {

		$saved_license 	= get_option( 'shareprints_license_key' );
		
		// not entered
		if($saved_license === ''){

			echo '<div class="update-nag">';
				echo '<strong>'.__('Notice:','shareprints').'</strong> '.__('There may be important updates available for SharePrints. Please enter your <a href="edit.php?post_type=shareprints&page=shareprints_settings&tab=license">license key</a> and go to Dashboard > Updates to check for updates.','shareprints');
			echo '</div>';

		}else{

			echo '<div class="update-nag">';
				echo '<strong>'.__('Notice:','shareprints').'</strong> '.__('There may be important updates available for SharePrints. Please verify that your <a href="edit.php?post_type=shareprints&page=shareprints_settings&tab=license">license key</a> has been entered correctly.','shareprints');
			echo '</div>';

		}

	}


// Media Library ID Column and Sort
	/**
	 *
	 * add_media_library_ID_column()
	 *
	 * adds ID column to media library
	 *
	 * @since 1.0.0
	 */
	function add_media_library_ID_column( $columns ) {
		$new = array();
		foreach($columns as $key => $title) {
		if ($key=='title') 
			$new['ID'] = 'File ID';
			$new[$key] = $title;
		}
		return $new;
	}
	
	/**
	 *
	 * media_library_ID_column_sort()
	 *
	 * adds ability to sort media library column
	 *
	 * @since 1.0.0
	 */
	function media_library_ID_column_sort( $columns ) {
		$columns['ID'] = 'ID';
		return $columns;
	}
	
	/**
	 *
	 * media_library_ID_column()
	 *
	 * populates the media id column with the image id
	 *
	 * @since 1.0.0
	 */
	function media_library_ID_column($column, $post_id){
		echo '#'.$post_id;
	}

	
// MCE Modal
	/**
	 *
	 * add_shareprints_media_button()
	 *
	 * adds the shortcode editor media button to the wp editor
	 *
	 * @since 1.0.0
	 */
	function add_shareprints_media_button(){
    	//$screen = get_current_screen();

    	//if( $screen && ($screen->post_type === 'shareprints') || ($screen->base !== 'page') && ($screen->base !== 'post') )
    	//return;

		//echo '<a class="button" id="shareprints_open" data-toggle="modal" data-target="#shareprints_mce_modal" title="'.__('Add Gallery', 'shareprints').'">'.__('Add Gallery', 'shareprints').'</a>';
		echo '<a class="button" id="shareprints_open" title="'.__('SharePrints', 'shareprints').'">'.__('SharePrints', 'shareprints').'</a>';
		
	}

	/**
	 *
	 * load_shareprints_mce_modal_template()
	 *
	 * outputs the shortcode editor popup template
	 *
	 * @since 1.0.0
	 */
	function load_shareprints_mce_modal_template(){
	
    	$screen = get_current_screen();
    	
		if( ($screen->post_type === $this->plugin_slug) || ($screen->base !== 'page' && $screen->base !== 'post') )
    	return;

		include_once( SHAREPRINTS_PLUGIN_DIR . 'admin/includes/mce_modal.php' );

	}


// AJAX
	/**
	 *
	 * return_shareprints_gallery()
	 *
	 * Returns a gallery for editing.
	 * called from mce modal
	 *
	 * @since 1.0.0
	 */
	function return_shareprints_gallery(){
		
		if( check_ajax_referer( 'shareprints-mce-modal-actions', 'security_seed', false ) && $_POST && isset($_POST['gallery_id']) ){
			
			$gallery_id = absint($_POST['gallery_id']);

			$post = ($gallery_id > 0) ? get_post($gallery_id) : false;
			
			$title = ($gallery_id > 0) ? $post->post_title : '';
			
			ob_start();

				echo '<div class="form-group">';
					echo '<label class="control-label" for="gallery_title">'.__('Title', 'shareprints').'</label>';
					echo '<div class="form-controls form-controls-special">';
						echo '<input type="text"  id="gallery_title" name="gallery_title" class="form-control" placeholder="'.__('Enter title here', 'shareprints').'"  data-error="'.__('Enter a title', 'shareprints').'" value="'.$title.'">';
					echo '</div>';
				echo '</div>';

				echo '<div class="meta-box-sortables ui-sortable">';
					echo '<div id="shareprints_gallery_images_meta_box" class="postbox shareprints_postbox no_box">';
						echo '<div class="inside">';
							do_action('render_shareprints_gallery_images_meta_box', $post );
						echo '</div>';
					echo '</div>';
				echo '</div>';

			echo ob_get_clean();

		}else{ 
			
			echo '"failure"';
		}
	
		die(); // this is required to return a proper result
		
	}

	/**
	 *
	 * delete_shareprints_gallery()
	 *
	 *
	 * @since 1.0.0
	 */
	function delete_shareprints_gallery(){
		
		if( check_ajax_referer( 'shareprints-mce-modal-actions', 'security_seed', false ) && $_POST && isset($_POST['gallery_id']) ){
			
			$gallery_id = absint($_POST['gallery_id']);
			
			$obj = get_post($gallery_id, OBJECT);
			
			$deleted = false;
			$response = 'failure';
			
			if($obj !== null && $obj->post_type === 'shareprints'){
				
				$deleted = wp_trash_post($gallery_id); // @future : can use wp_untrash_post to undo this action.
				
			}
			
			if($deleted){

				$response = array(
					'ID' => $obj->ID,
					'msg' => $obj->post_title.__(' moved to trash.','shareprints'),
				);

			}
			
			echo json_encode($response);

		}else{ 
			
			echo '"failure"';
		}
	
		die(); // this is required to return a proper result
		
	}

	/**
	 *
	 * save_update_shareprints_gallery()
	 *
	 *
	 * @since 1.0.0
	 */
	function save_update_shareprints_gallery(){
		
		if( check_ajax_referer( 'shareprints-mce-modal-actions', 'security_seed', false ) && $_POST && isset($_POST['shareprints_images_nonce']) ){
			
			$success = false;
			$response = 'failure';
			$action = isset($_POST['sp_action']) ? $_POST['sp_action'] : false;
			
			$values = array(
				'ID' => isset($_POST['ID']) ? absint($_POST['ID']) : '',
				'post_title' => wp_strip_all_tags($_POST['post_title'], true),
				'shareprints_images' => isset($_POST['shareprints_images']) ? (array)$_POST['shareprints_images'] : '',
				'shareprints_images_nonce' => $_POST['shareprints_images_nonce'],
			);
			
			if($action === "edit"){
				
				$success = wp_update_post( $values );
				
			}

			if($action === "create"){
			
				//unset($values['ID']);
				
				$values['post_type'] = 'shareprints';
				$values['post_status'] = 'publish';
				
				$success = wp_insert_post( $values );
				
			}

			if($success){
				
				$values['ID'] = $success;
				
				$response = $values;
				
			}
			
			echo json_encode($response);

		}else{ 
			
			echo '"failure"';
		}
	
		die(); // this is required to return a proper result
		
	}

	/**
	 *
	 * save_mce_modal_settings()
	 *
	 * Saves shortcode editor settings
	 *
	 * @since 1.0.0
	 */
	function save_mce_modal_settings(){
	
		if( check_ajax_referer( 'shareprints-mce-modal-actions', 'security_seed', false ) && $_POST && isset($_POST['values']) ){
			
			$values	= $_POST['values'];
			
			$values['save_name'] = sanitize_text_field($values['save_name']);
			
			$values['settings_id'] = wp_create_nonce($values['save_name']);
						
			$favorites = (array) get_option('shareprints_mce_favorites');
				
			$favorites[$values['settings_id']] = $values;
				
			update_option('shareprints_mce_favorites', $favorites);
			
			$result = array(
				'save_name' => stripslashes($values['save_name']),
				'settings_id' => $values['settings_id'],
				'values' => $values,
			);
			
			echo json_encode($result);
			
		}else{ 
			
			echo '"'.__('Security Error','shareprints').'"';
		}
	
		die(); // this is required to return a proper result
		
	}
	
	/**
	 *
	 * delete_mce_modal_settings()
	 *
	 * Deletes saved shortcode editor settings
	 *
	 * @since 1.0.0
	 */
	function delete_mce_modal_settings(){

		if( check_ajax_referer( 'shareprints-mce-modal-actions', 'security_seed', false ) && $_POST && isset($_POST['save_name']) && isset($_POST['settings_id']) ){
		
			$save_name   = sanitize_text_field(stripslashes($_POST['save_name']));
			
			$settings_id   = $_POST['settings_id'];
			
			$favorites = (array) get_option('shareprints_mce_favorites');
			
			if( isset($favorites[$settings_id]) && $favorites[$settings_id]['save_name'] === $save_name ) {

				unset($favorites[$settings_id]);
			
				update_option('shareprints_mce_favorites', $favorites);
				
				$count = count($favorites);
				
				$result = array(
						'save_name' 	=> $save_name,
						'settings_id'	=> $settings_id,
						'count' => $count,
						);
						
				echo json_encode($result);
				
			}else{
				
				echo '"'.__('Error: Saved Setting Not Found','shareprints').'"';
				
			}
						
		}else{ 
			
			echo '"'.__('Security Error','shareprints').'"';
		}
	
		die(); // this is required to return a proper result
		
	}


// Thumbnails 
	/**
	 *
	 * shareprints_thumbs_generated_meta_field()
	 *
	 * Tags media items as they are uploaded. This is checked when images are added to galleries. If it doesn't exist then our thumbs are generated.
	 *
	 *
	 * @since 1.0.0
	 */
	function shareprints_thumbs_generated_meta_field($metadata){
		
		if(isset($metadata['sizes']['shareprints_thumb'])){
			
			$metadata['shareprints'] = true;
			
		}
		
		return $metadata;
		
	}

	/**
	 *
	 * wp_prepare_attachment_for_js()
	 *
	 * Preps attachments for the media library script
	 *
	 *
	 * @since 1.0.0
	 */
	function wp_prepare_attachment_for_js( $response, $attachment, $meta ){
	
		if( $response['type'] != 'image' )
		return $response;
		
		if( !isset($meta['sizes']) )
		return $response;

		$attachment_url = $response['url'];
		$base_url = str_replace( wp_basename( $attachment_url ), '', $attachment_url );
		
		if(isset($meta['shareprints']))
		$response['shareprints'] = true;
	
		$response['layout'] = ($response['width'] >= $response['height']) ? ( ($response['width'] == $response['height']) ? 'square' : 'landscape' ) : 'portrait';

		if( isset($meta['sizes']) && is_array($meta['sizes']) ){
		
			foreach( $meta['sizes'] as $k => $v ){
				if( !isset($response['sizes'][ $k ]) ){
					$response['sizes'][ $k ] = array(
						'height'      =>  $v['height'],
						'width'       =>  $v['width'],
						'url'         => $base_url .  $v['file'],
					);
				}
			}
		
		}

		return $response;
	}

	/**
	 *
	 * shareprints_check_thumbs()
	 *
	 * Checks images as they are uploaded to a gallery to insure our thumbanils exist
	 * If thumbs dont exist they are created
	 *
	 * @since 1.0.0
	 */
	function shareprints_check_thumbs(){
		
		@error_reporting( 0 );
	
		header( 'Content-type: application/json' );

		if( check_ajax_referer( 'check-for-thumbs', 'security_seed', false ) && $_POST && isset($_POST['imageID']) && ($_POST['imageID'] != '') ){

			$imageID = intval($_POST['imageID']);
			
			$attachment = get_post( $imageID );
			
			$metadata = wp_get_attachment_metadata($imageID);
			
			if( isset($metadata['shareprints']) && $metadata['shareprints'] !== false ){
				
				// Thumbs have already been created so stop here
				echo '"success"';
				die();
				
			}else{

				if( 'image/' != substr( $attachment->post_mime_type, 0, 6 ) || 'bmp' === substr( $attachment->post_mime_type, 6, 3 ) ){
					echo '"'.__('Unsupported media type','shareprints').'"';
					die();
				}
				
				$fullsizepath = get_attached_file( $imageID );
				
				if( (false === $fullsizepath) || !file_exists($fullsizepath) ){
					echo '"'.__('Image not found','shareprints').'"';
					die();
				}
				
				@set_time_limit( 900 );
				
				$new_metadata = (array)$this->shareprints_generate_attachment_metadata( $imageID, $fullsizepath );
				
				if( is_wp_error($new_metadata) || empty($new_metadata) ){
					echo '"'.__('Unknown wordpress error','shareprints').'"';
					die();
				}
				
				wp_update_attachment_metadata( $imageID, $new_metadata );

				echo '"success"';
				die();
				
			}
			
		}else{ 
			
			echo '"'.__('Network Error','shareprints').'"';
		}
	
		die(); // this is required to return a proper result
		
	}

	/**
	 *
	 * shareprints_generate_attachment_metadata()
	 *
	 * Called by check_thumbs from the gallery editor page
	 * creates out thumb sizes on demand.
	 *
	 * @since 1.0.0
	 */
	function shareprints_generate_attachment_metadata( $attachment_id, $file ) {
	
		$attachment = get_post( $attachment_id );
	
		// Get the existing metadata
		$metadata = wp_get_attachment_metadata($attachment_id);
		
		if ( preg_match('!^image/!', get_post_mime_type( $attachment )) ) {
	
			// get our image sizes
			$sp_sizes = (array)$this->plugin->shareprints_image_sizes();
			
			$editor = wp_get_image_editor( $file );
			
			if ( ! is_wp_error( $editor ) ){ // if there is an error, nothing will happen, the metadata will simply be overwritten by itself. 
				
				$newSizes = $editor->multi_resize( $sp_sizes );
				
				$metadata['sizes'] = array_merge( (array)$metadata['sizes'], $newSizes );
				
			}
	
		} 
		
		// remove the blob of binary data from the array
		unset( $metadata['image']['data'] );
	
		return apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
		
	}

}