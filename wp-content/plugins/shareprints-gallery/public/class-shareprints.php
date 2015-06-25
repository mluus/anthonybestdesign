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
class Shareprints {
	
	/**
	 *
	 * @since 1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'shareprints';

	/**
	 *
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 *
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

		// Init
		add_action( 'init', array( $this, 'init' ));

		// Load Scripts and Styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

		// Header Meta Output
		add_action( 'wp_head', array( $this, 'shareprints_header_output' ), 1 );

		// Shortcode Output
		add_shortcode( 'shareprints',  array( $this, 'shareprints_shortcode' ));
		
		// Shareprints CPT Update Messages
		add_filter( 'post_updated_messages', array( $this, 'shareprints_cpt_update_messages' ));
		
		// CPT Meta Box
		add_filter('return_shareprints_gallery_images_meta_box_value', array( $this, 'return_shareprints_gallery_images_meta_box_value' ), 10, 1);
		add_action('save_post', array( $this, 'save_shareprints_gallery_images_meta_value'), 10, 1);
		add_action('render_shareprints_gallery_images_meta_box', array( $this, 'render_shareprints_gallery_images_meta_box'), 10, 1);

		// Add links to admin bar
		add_action( 'wp_before_admin_bar_render', array($this, 'add_shareprints_admin_bar_links') ); 

		// Gallery Output
		add_action('shareprints/create_gallery', array($this, 'create_gallery'), 5, 1);
		add_filter('shareprints/load_gallery_defaults', array($this, 'load_gallery_defaults'), 5, 1);

		// Gallery images filter
		add_filter('return_shareprints_gallery_image_array', array( $this, 'return_shareprints_gallery_image_array' ), 10, 2);

		
	}

	/**
	 *
	 * init()
	 *
	 * Init
	 *
	 * @since 1.0.0
	 */
	function init(){

		// Load plugin text domain
		$this->load_plugin_textdomain();

		// Register shareprints custom post type
		$this->register_shareprints_cpt();
		
		// Register shareprints scripts and styles
		$this->register_shareprints_scripts_and_styles();
		
		// Localize shareprints scripts
		$this->localize_shareprints_scripts();
				
		// Add image sizes
		$this->add_shareprints_image_sizes();
	
		// Load base gallery and lightbox class
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/includes/class-shareprints-gallery.php');
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/includes/class-shareprints-lightbox.php');
		
		// Core Gallery Types
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/views/gallery-types/masonry.php');
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/views/gallery-types/squares.php');
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/views/gallery-types/blog.php');
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/views/gallery-types/filmstrip.php');
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/views/gallery-types/slider.php');
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/views/gallery-types/thumb-slider.php');
		include_once( SHAREPRINTS_PLUGIN_DIR . 'public/views/gallery-types/slidescroll.php');
				
		// Include 3rd party addons
		do_action('shareprints/register_addons');
		
	}

	/**
	 *
	 * get_plugin_slug()
	 *
	 * Return the plugin slug.
	 *
	 * @since 1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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


// Plugin Activation / Deactivation
	/**
	 *
	 * shareprints_activation()
	 *
	 * Sets a transient on activation to trigger the welcome page.
	 *
	 * @since 1.0.0
	 */
	public static function shareprints_activation() {
		
		// Bail if activating from network, or bulk
		if( is_network_admin() || isset($_GET['activate-multi']) )
		return;

		// Add the transient to redirect
		set_transient( 'shareprints_activation_redirect', true, 30 );

	}

	/**
	 *
	 * load_plugin_textdomain()
	 *
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}


// CPT
	/**
	 *
	 * register_shareprints_cpt()
	 *
	 * Register Shareprints Custom Post Type
	 *
	 * @since 1.0.0
	 */
	private function register_shareprints_cpt(){
		
		$labels = array(
		    'name'               => 'SharePrints',
		    'singular_name'      => 'SharePrints',
		    'add_new'            => __('Add New Gallery','shareprints'),
		    'add_new_item'       => __('Add New Gallery','shareprints'),
		    'edit_item'          => __('Edit Gallery','shareprints'),
		    'new_item'           => __('New Gallery','shareprints'),
		    'all_items'          => __('All Galleries','shareprints'),
		    'view_item'          => __('View Gallery','shareprints'),
		    'search_items'       => __('Search Galleries','shareprints'),
		    'not_found'          => __('No Galleries found','shareprints'),
		    'not_found_in_trash' => __('No Galleries found in Trash','shareprints'),
		    'parent_item_colon'  => '',
		    'menu_name'          => 'SharePrints'
		);
		
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'query_var' => false,
			'rewrite' => false,//true,
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'supports' => array( 'title' ),
			'menu_position' => 5,
			'menu_icon' => SHAREPRINTS_PLUGIN_URL . 'assets/img/menu-icon.png',
			'register_meta_box_cb' => array($this, 'add_shareprints_meta_boxes')
		);
		
		register_post_type( $this->plugin_slug, $args );
	
	}

	/**
	 *
	 * shareprints_cpt_update_messages()
	 *
	 * Shareprints cpt update messages
	 *
	 * @since 1.0.0
	 */
	public function shareprints_cpt_update_messages($messages){
		global $post;
		
		$messages[$this->plugin_slug] = array(
				0 => '', 
				1 => __( 'Gallery updated.' , 'shareprints' ),
				2 => __( 'Gallery Custom field updated.' , 'shareprints' ),
				3 => __( 'Gallery Custom field deleted.' , 'shareprints' ),
				4 => __( 'Gallery updated.' , 'shareprints' ),
				5 => isset($_GET['revision']) ? sprintf( __( 'Post restored to revision from %s.' , 'shareprints' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => __( 'Gallery Published.' , 'shareprints' ),
				7 => __( 'Gallery saved.' , 'shareprints' ),
				8 => __( 'Gallery submitted.' , 'shareprints' ),
				9 => sprintf( __( 'Gallery scheduled for: %1$s.' , 'shareprints' ), '<strong>' . date_i18n( __( 'M j, Y @ G:i' , 'shareprints' ), strtotime( $post->post_date ) ) . '</strong>' ),
				10 => __( 'Gallery draft updated.' , 'shareprints' ),
			);
		
		return $messages;
	
	}

	/**
	 *
	 * add_shareprints_meta_boxes()
	 *
	 * adds the gallery images meta box to our cpt
	 *
	 * @since 1.0.0
	 */
	public function add_shareprints_meta_boxes(){
	
		add_meta_box('shareprints_gallery_images_meta_box',	__('Gallery Images','shareprints'), array($this, 'render_shareprints_gallery_images_meta_box'), $this->plugin_slug, 'normal', 'high' );
	
	}

	/**
	 *
	 * add_shareprints_admin_bar_links()
	 *
	 * Outputs a SharePrints dropdown in the admin bar
	 *
	 * @param	$post_id - is the id of the cpt post (gallery)
	 * @since 1.0.0
	 */
	function add_shareprints_admin_bar_links() {
		global $wp_admin_bar;

		$title = '<span class="ab-icon"><img src="'.SHAREPRINTS_PLUGIN_URL . 'assets/img/menu-icon.png"></span>';
		$title .= '<span class="screen-reader-text">'.__('SharePrints','shareprints').'</span>';

		$submenu = '<div style="margin:5px 0;"><ul class="ab-sub-secondary ab-submenu">';
			$submenu .= '<li><a href="'.admin_url().'edit.php?post_status=publish&post_type=shareprints">'.__('Published','shareprints').' <span>('.wp_count_posts('shareprints')->publish.')</span></a></li>';
			$submenu .= '<li><a href="'.admin_url().'edit.php?post_status=draft&post_type=shareprints">'.__('Draft','shareprints').' <span>('.wp_count_posts('shareprints')->draft.')</span></a></li>';
			$submenu .= '<li><a href="'.admin_url().'edit.php?post_status=trash&post_type=shareprints">'.__('Trash','shareprints').' <span>('.wp_count_posts('shareprints')->trash.')</span></a></li>';
		$submenu .= '</ul></div>';

		$wp_admin_bar->add_menu( array(
			'id'    => 'shareprints_admin_bar_menu',
			'title' => $title,
			'href'  => admin_url().'edit.php?post_type=shareprints'
		));
		$wp_admin_bar->add_menu( array(
			'id'    => 'shareprints_admin_bar_menu-all',
			'title' => __('All Galleries','shareprints'),
			'href'  => admin_url().'edit.php?post_type=shareprints',
			'parent'=>'shareprints_admin_bar_menu',
			'meta'      => array(
				'html'     => $submenu,
			),
		));
		$wp_admin_bar->add_menu( array(
			'id'    => 'shareprints_admin_bar_menu-new',
			'title' => __('Add New Gallery','shareprints'),
			'href'  => admin_url().'post-new.php?post_type=shareprints',
			'parent'=>'shareprints_admin_bar_menu'
		));

	}

	/**
	 *
	 * render_shareprints_gallery_images_meta_box()
	 *
	 * This function renders the shareprints_gallery_images_meta_box meta box for the shareprints cpt
	 *
	 * @param	$post 
	 * @return	(html)
	 * @since 1.0.0
	 */
	public function render_shareprints_gallery_images_meta_box( $post ){
	
		if($post !== false){	
			$images = apply_filters('return_shareprints_gallery_image_array', $post->ID, false);
		}else{
			$images = array();


			// Schedule auto-draft cleanup
			if ( ! wp_next_scheduled( 'wp_scheduled_auto_draft_delete' ) )
			wp_schedule_event( time(), 'daily', 'wp_scheduled_auto_draft_delete' );
	
			$post = get_default_post_to_edit( 'shareprints', true );

		}
		
		ob_start();
		echo '<div id="shareprints_gallery_images" data-id="'.$post->ID.'">';
			echo '<input type="hidden" id="shareprints_images_nonce" name="shareprints_images_nonce" value="'.wp_create_nonce( 'shareprints-gallery-images' ).'"/>';
			echo '<p class="label"><label>'.__('Gallery Images','shareprints').'</label></p>';
			echo '<div class="shareprints-images" data-preview_size="shareprints_320" data-library="all">';

				echo '<div class="top-toolbar toolbar">';
					echo '<div class="gallery-li view-smaller-li"><a class="ir view-smaller" href="#" title="'.__('View Small Grid','shareprints').'">'.__("Smaller",'shareprints').'</a></div>';
					echo '<div class="gallery-li view-grid-li active"><a class="ir view-grid" href="#" title="'.__('View Grid','shareprints').'">'.__("Grid",'shareprints').'</a></div>';
					echo '<div class="gallery-li view-list-li"><a class="ir view-list" href="#" title="'.__('View List','shareprints').'">'.__("List",'shareprints').'</a></div>';
				echo '</div>';


				echo '<input type="hidden" id="shareprints_images" name="shareprints_images" value="" />';
				echo '<div class="thumbnails">';
					echo '<div class="inner">';
					if( $images ){ 
						foreach( $images as $image ): 
						$src = ( strpos($image['mime_type'], 'image') !== false ) ? $image['sizes']['shareprints_320'] : wp_mime_type_icon( $image['id'] );
						echo '<div class="thumbnail" data-id="'.$image['id'].'">';
							echo '<input class="shareprints-image-value" type="hidden" name="shareprints_images[]" value="'.$image['id'].'" />';
							echo '<div class="inner">';
								echo '<img src="'.$src.'" alt="" data-layout="'.$image['orientation'].'"/>';
								echo '<div class="list-data">';
									echo '<ul class="tabs">';
										echo '<li><a class="tab active" href="#" data-target="image_'.$image['id'].'_1" title="'.__('View Title','shareprints').'">'.__("Title",'shareprints').'</a></li>';
										echo '<li><a class="tab" href="#" data-target="image_'.$image['id'].'_2" title="'.__('View Caption','shareprints').'">'.__("Caption",'shareprints').'</a></li>';
										echo '<li><a class="tab" href="#" data-target="image_'.$image['id'].'_3" title="'.__('View Description','shareprints').'">'.__("Description",'shareprints').'</a></li>';
										echo '<li><a class="tab" href="#" data-target="image_'.$image['id'].'_4" title="'.__('View Alternate Text','shareprints').'">'.__("Alternate Text",'shareprints').'</a></li>';
									echo '</ul>';
									echo '<div id="image_'.$image['id'].'_1" class="tab-panel active"><p class="td-title">'.$image['title'].'</p></div>';
									echo '<div id="image_'.$image['id'].'_2" class="tab-panel"><p class="td-caption">'.$image['caption'].'</p></div>';
									echo '<div id="image_'.$image['id'].'_3" class="tab-panel"><p class="td-description">'.$image['description'].'</p></div>';
									echo '<div id="image_'.$image['id'].'_4" class="tab-panel"><p class="td-alt">'.$image['alt'].'</p></div>';
								echo '</div>';
							echo '</div>';
							echo '<div class="hover">';
								echo '<ul class="bl">';
									echo '<li><a href="#" class="shareprints-button-delete ir" title="'.__('Remove Image','shareprints').'">'.__("Remove",'shareprints').'</a></li>';
									echo '<li><a href="#" class="shareprints-button-edit ir" title="'.__('Edit Image','shareprints').'">'.__("Edit",'shareprints').'</a></li>';
								echo '</ul>';
							echo '</div>';
						echo '</div>';
						endforeach; 
					}
					echo '</div>';
				echo '</div>';
				echo '<div class="toolbar">';
					echo '<ul class="hl clearfix">';
						echo '<li class="add-image-li"><a class="shareprints-button add-image" href="#" title="'.__('Add Images','shareprints').'">'.__("Add Images",'shareprints').'</a></li>';
						echo '<li class="gallery-li view-grid-li active"><a class="ir view-grid" href="#" title="'.__('View Grid','shareprints').'">'.__("Grid",'shareprints').'</a></li>';
						echo '<li class="gallery-li view-list-li"><a class="ir view-list" href="#" title="'.__('View List','shareprints').'">'.__("List",'shareprints').'</a></li>';
						echo '<li class="gallery-li view-smaller-li"><a class="ir view-smaller" href="#" title="'.__('View Small Grid','shareprints').'">'.__("Smaller",'shareprints').'</a></li>';
						echo '<li class="gallery-li count-li right"><span class="count"></span></li>';
					echo '</ul>';
				echo '</div>';
				echo '<script type="text/html" class="tmpl-thumbnail">';
					echo '<div class="thumbnail" data-id="{id}">';
						echo '<input type="hidden" class="shareprints-image-value" name="shareprints_images[]" value="{id}" />';
						echo '<div class="inner">';
							echo '<img src="{url}" alt="{alt}" data-layout="{layout}"/>';
							echo '<div class="list-data">';
								echo '<ul class="tabs">';
									echo '<li><a class="tab active" href="#" data-target="image_{id}_1" title="'.__('View Title','shareprints').'">'.__("Title",'shareprints').'</a></li>';
									echo '<li><a class="tab" href="#" data-target="image_{id}_2" title="'.__('View Caption','shareprints').'">'.__("Caption",'shareprints').'</a></li>';
									echo '<li><a class="tab" href="#" data-target="image_{id}_3" title="'.__('View Description','shareprints').'">'.__("Description",'shareprints').'</a></li>';
									echo '<li><a class="tab" href="#" data-target="image_{id}_4" title="'.__('View Alternate Text','shareprints').'">'.__("Alternate Text",'shareprints').'</a></li>';
								echo '</ul>';
								echo '<div id="image_{id}_1" class="tab-panel active"><p class="td-title">{title}</p></div>';
								echo '<div id="image_{id}_2" class="tab-panel"><p class="td-caption">{caption}</p></div>';
								echo '<div id="image_{id}_3" class="tab-panel"><p class="td-description">{description}</p></div>';
								echo '<div id="image_{id}_4" class="tab-panel"><p class="td-alt">{alt}</p></div>';
							echo '</div>';
						echo '</div>';
						echo '<div class="hover">';
							echo '<ul class="bl">';
								echo '<li><a href="#" class="shareprints-button-delete ir" title="'.__('Remove Image','shareprints').'">'.__('Remove','shareprints').'</a></li>';
								echo '<li><a href="#" class="shareprints-button-edit ir" title="'.__('Edit Image','shareprints').'">'.__('Edit','shareprints').'</a></li>';
								echo '';
							echo '</ul>';
						echo '</div>';
					echo '</div>';
				echo '</script>';
			echo '</div>';
		echo '</div>';
/*
		echo '<script type="text/javascript">';
			echo '(function($) {';
				echo "$('#shareprints_gallery_images_meta_box').addClass('shareprints_postbox no_box').removeClass('hide-if-js');";
			echo '})(jQuery);';	
		echo '</script>';
*/
		echo ob_get_clean();
		
	}

	/**
	 *
	 * return_shareprints_gallery_images_meta_box_value()
	 *
	 * This filter returns the raw image id's saved in a shareprints cpt shareprints_gallery_images_meta_box meta box
	 *
	 * @param	$post_id - is the id of the cpt post
	 * @return	(array)
	 * @since 1.0.0
	 */
	public function return_shareprints_gallery_images_meta_box_value( $post_id ){

		$found = false;
		
		$cache = wp_cache_get( 'shareprints_gallery_images_meta_box_value/post_id='.$post_id, 'shareprints', false, $found );

		if( $found )
		return $cache;

		$value = false;

		$v = get_post_meta( $post_id, 'shareprints_images', false );
		
		if( isset($v[0]) )
	 	$value = $v[0];

		$value = maybe_unserialize($value);

		wp_cache_set( 'shareprints_gallery_images_meta_box_value/post_id='.$post_id, $value, 'shareprints' );

		return $value;

	}

	/**
	 *
	 * return_shareprints_gallery_image_array()
	 *
	 * This filter returns an associative array of shareprints gallery images
	 *
	 * @param	$post_id - is the id of the cpt post (gallery)
	 *			$full - if false output will only include 'shareprints_320' in the image[sizes]
	 * @return	(array)
	 * @since 1.0.0
	 */
	public function return_shareprints_gallery_image_array( $post_id, $full = true ) {

		// holder for our return
		$images = array();
		$ordered_attachments = array();

		// get shareprints image sizes
		$image_sizes = $this->shareprints_image_sizes();

		// load our cpt meta box value
		$value = apply_filters('return_shareprints_gallery_images_meta_box_value', $post_id);

		// empty
		if( empty($value) || !is_array($value) )
		return $value;

		// find attachments
		$attachments = get_posts(array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post__in' => $value,
		));
		
		// load the images holder
		if($attachments)
		foreach( $attachments as $attachment){
		
			if( strpos($attachment->post_mime_type, 'image') !== false ){
			
				$src = wp_get_attachment_image_src( $attachment->ID, 'full' );
			
				//$images[] = array(
				$image = array(
					'id'			=>	$attachment->ID,
					'alt'			=>	get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
					'title'			=>	$attachment->post_title,
					'caption'		=>	$attachment->post_excerpt,
					'description'	=>	$attachment->post_content,
					'mime_type'		=>	$attachment->post_mime_type,
					'url' 			=> $src[0],
					'width' 		=> $src[1],
					'height' 		=> $src[2],
					'orientation'	=> ($src[1] >= $src[2]) ? ( ($src[1] === $src[2]) ? 'square' : 'landscape' ) : 'portrait',
					'sizes'			=> array(),
				);
				
				if($full){
					foreach( $image_sizes as $image_size => $array){
					
						// find src
						$src = wp_get_attachment_image_src( $attachment->ID, $image_size );
						
						// add src
						$image['sizes'][ $image_size ] = $src[0];
						$image['sizes'][ $image_size . '-width' ] = $src[1];
						$image['sizes'][ $image_size . '-height' ] = $src[2];
						
					}
				}else{
						$src = wp_get_attachment_image_src( $attachment->ID, 'shareprints_320' );
						
						// add src
						$image['sizes'][ 'shareprints_320' ] = $src[0];
						$image['sizes'][ 'shareprints_320-width' ] = $src[1];
						$image['sizes'][ 'shareprints_320-height' ] = $src[2];
					
				}
				
				//$images[] = $image;
				$ordered_attachments[$image['id']] = $image;
				
			}
			
		}
		
		foreach( $value as $v){
			if( isset($ordered_attachments[ $v ]) ){
				$images[] = $ordered_attachments[ $v ];
			}
		}
		
		// @future : could use the file name instead of the entire url for each size.
/*
		foreach( $attachments as $attachment){
		
			if( strpos($attachment->post_mime_type, 'image') !== false ){
			
				$src = get_post_meta( $attachment->ID, '_wp_attachment_metadata', true );
			
				//$images[] = array(
				$image = array(
					'id'			=>	$attachment->ID,
					'alt'			=>	get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
					'title'			=>	$attachment->post_title,
					'caption'		=>	$attachment->post_excerpt,
					'description'	=>	$attachment->post_content,
					'mime_type'		=>	$attachment->post_mime_type,
					'url' 			=> $src['file'],
					'width' 		=> $src['width'],
					'height' 		=> $src['height'],
					'sizes'			=> array(),
				);
				
				$last_used = false;
				foreach( $image_sizes as $image_size => $array){
					
					if(isset($src['sizes'][$image_size])){

						// add src
						$file 	= $src['sizes'][$image_size]['file'];
						$width	= $src['sizes'][$image_size]['width'];
						$height	= $src['sizes'][$image_size]['height'];
						
						$last_used = $image_size;
						
					}else{
					
						if($last_used){
							// revert to last used
							$file	= $src['sizes'][$last_used]['file'];
							$width	= $src['sizes'][$last_used]['width'];
							$height	= $src['sizes'][$last_used]['height'];
						}else{
							// revert to orignal
							$file	= $src['file'];
							$width	= $src['width'];
							$height	= $src['height'];
						}
						
					}
					
					// load final sizes value
					$image['sizes'][ $image_size ]				= $file;
					$image['sizes'][ $image_size . '-width' ]	= $width;
					$image['sizes'][ $image_size . '-height' ]	= $height;
					
				}
				
				$images[] = $src;//$image;
				
			}
			
		}
*/
		
		// return value
		return $images;
	 
	}

	/**
	 *
	 * save_shareprints_gallery_images_meta_value()
	 *
	 * Saves the meta box value for the shareprints cpt
	 *
	 * @param	$post_id - is the id of the cpt post (gallery)
	 * @since 1.0.0
	 */
	function save_shareprints_gallery_images_meta_value($post_id) {

		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;
		
		if( !isset($_POST['shareprints_images_nonce'], $_POST['shareprints_images']) || !wp_verify_nonce($_POST['shareprints_images_nonce'], 'shareprints-gallery-images') )
		return $post_id;
		
		if( !isset($_POST['shareprints_images']))
		return $post_id;

		$value = stripslashes_deep($_POST['shareprints_images']);

		update_metadata('post', $post_id, 'shareprints_images', $value );
	
		wp_cache_set( 'shareprints_gallery_images_meta_box_value/post_id='.$post_id, $value, 'shareprints' );

		return $post_id;

	}


// Shortcode Output
	/**
	 *
	 * shareprints_shortcode()
	 *
	 * Outputs the SharePrints Shortcode
	 *
	 * @param $atts - shortcode attributes
	 * @since 1.0.0
	 */
	public function shareprints_shortcode( $atts, $content = null ){
		
		$gallery = shortcode_atts( array(
			'gallery_id' 		=> false,
			'gallery_title'		=> false,
			'gallery_type'		=> 'masonry',
			'image_size'		=> 'small',
			'image_padding'		=> false,
			'image_hover'		=> false,
			'gallery_position'	=> 'pos_center',
			'gallery_width'		=> 'width_100',
			'theme'				=> 'dark',
			'lightbox_type'		=> false,
			'titles'			=> false,
			'captions'			=> false,
			'descriptions'		=> false,
			'comments'			=> false,
			'sharing'			=> false,
		), $atts, 'shareprints' ) ;
		
		if( !$gallery['gallery_id'] ) return;
		
		// Bail if gallery is not published
		if("publish" !== get_post_status( absint($gallery['gallery_id']) ))
		return;

		// sanitize the attr values
		$gallery['gallery_id'] 		= absint($gallery['gallery_id']);
		$gallery['gallery_title'] 	= get_the_title($gallery['gallery_id']);
		$gallery['image_size']		= (in_array($gallery['image_size'], array('small', 'medium', 'large', 'xlarge'))) ? $gallery['image_size'] : 'small';
		$gallery['image_padding'] 	= absint($gallery['image_padding']);
		$gallery['image_hover']		= ($gallery['lightbox_type'] !== 'false') ? ($gallery['image_hover'] !== 'false' ? ' '.$gallery['image_hover'] : '') : ' nohvr';
		$gallery['lightbox_type']	= ($gallery['lightbox_type'] !== 'false') ? $gallery['lightbox_type'] : false;
		$gallery['theme']			= ($gallery['theme'] === "dark") ? "dark" : "light";
		$gallery['titles']			= ($gallery['titles'] && $gallery['titles'] !== 'false') ? true : false;
		$gallery['captions']		= ($gallery['captions'] && $gallery['captions'] !== 'false') ? true : false;
		$gallery['descriptions']	= ($gallery['descriptions'] && $gallery['descriptions'] !== 'false') ? true : false;
		$gallery['comments']		= ($gallery['comments'] !== 'false') ? true : false;
		$gallery['sharing']			= ($gallery['sharing'] !== 'false') ? true : false;
		$gallery['gallery_images']  = apply_filters('return_shareprints_gallery_image_array', $gallery['gallery_id']);

		ob_start();

			if($gallery['gallery_images']){
			
				do_action('shareprints/before_shareprints_container', $gallery);
					
					echo '<div class="shareprints_container '.$gallery['gallery_position'].' '.$gallery['gallery_width'].' ">';
					
						do_action('shareprints/shareprints_container_start', $gallery);
		
							do_action('shareprints/create_gallery', $gallery);
					
						do_action('shareprints/before_gallery_container_end', $gallery);
					
					echo '</div>';
					
				do_action('shareprints/after_shareprints_container', $gallery);

				// enqueue lightbox template if any
				if( $gallery['lightbox_type'] ){

					//do_action('shareprints/create_lightbox', $lightbox);
					do_action('shareprints/create_lightbox');
					
				};
			
			}
			
		return ob_get_clean();
		
	}


// Scripts / Styles
	/**
	 *
	 * register_shareprints_scripts_and_styles()
	 *
	 * Register Shareprints Scripts and Styles
	 *
	 * @since 1.0.0
	 */
	private function register_shareprints_scripts_and_styles(){

		$styles = array(
			// Front End
			array(
				'handle' => 'shareprints-fe-style',
				'src' 	 => SHAREPRINTS_PLUGIN_URL . 'assets/css/shareprints-fe-style.css',
				'deps' 	 => '',
				'ver' 	 => SHAREPRINTS_VERSION,
				'media'  => 'all' 
			),

			// Back End
			array(
				'handle' => 'shareprints-settings-page-style',
				'src' 	 => SHAREPRINTS_PLUGIN_URL . 'assets/css/shareprints-settings-page-style.css',
				'deps' 	 => '',
				'ver' 	 => SHAREPRINTS_VERSION,
				'media'  => 'screen' 
			),
			array(
				'handle' => 'shareprints-media-library-style',
				'src' 	 => SHAREPRINTS_PLUGIN_URL . 'assets/css/shareprints-media-library-style.css',
				'deps' 	 => '',
				'ver' 	 => SHAREPRINTS_VERSION,
				'media'  => 'all' 
			),
			array(
				'handle' => 'shareprints-images-style',
				'src' 	 => SHAREPRINTS_PLUGIN_URL . 'assets/css/shareprints-images-style.css',
				'deps' 	 => '',
				'ver' 	 => SHAREPRINTS_VERSION,
				'media'  => 'all' 
			),

			array(
				'handle' => 'shareprints-mce-modal-style',
				'src' 	 => SHAREPRINTS_PLUGIN_URL . 'assets/css/shareprints-mce-modal-style.css',
				'deps' 	 => '',
				'ver' 	 => SHAREPRINTS_VERSION,
				'media'  => 'all' 
			),


		);
		
		foreach( $styles as $style ){
			wp_register_style( $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
		}

		$scripts = array(
			// Front End
			array(
				'handle' 	=> 'shareprints_js_plugins',
				'src' 		=> SHAREPRINTS_PLUGIN_URL . 'assets/js/shareprints_js_plugins-jr.js',
				'deps' 		=> array('jquery'),
				'ver' 	 	=> SHAREPRINTS_VERSION,
				'in_footer' => true 
			),
			array(
				'handle' 	=> 'shareprints_lightbox',
				'src' 		=> SHAREPRINTS_PLUGIN_URL . 'assets/js/shareprints_lightbox-jr.js',
				'deps' 		=> array('jquery', 'shareprints_js_plugins'),
				'ver' 		 => SHAREPRINTS_VERSION,
				'in_footer' => true 
			),
			array(
				'handle' 	=> 'shareprints_loader',
				'src' 		=> SHAREPRINTS_PLUGIN_URL . 'assets/js/shareprints_loader-jr.js',
				'deps' 		=> array('jquery'),
				'ver' 		 => SHAREPRINTS_VERSION,
				'in_footer' => true 
			),

			// Back End
			array(
				'handle' 	=> 'shareprints-images-script',
				'src' 		=> SHAREPRINTS_PLUGIN_URL . 'assets/js/shareprints-images-script-jr.js',
				'deps' 		=> '',
				'ver' 		=> SHAREPRINTS_VERSION,
				'in_footer' => true 
			),

			array(
				'handle' 	=> 'shareprints-bootstrap-js',
				'src' 		=> SHAREPRINTS_PLUGIN_URL . 'assets/bootstrap/js/bootstrap-jr.js',
				'deps' 		=> array('jquery'),
				'ver' 		=> SHAREPRINTS_VERSION,
				'in_footer' => true 
			),
			array(
				'handle' 	=> 'shareprints-mce-modal-script',
				'src' 		=> SHAREPRINTS_PLUGIN_URL . 'assets/js/shareprints-mce-modal-script-jr.js',
				'deps' 		=> array('jquery', 'shareprints-bootstrap-js'),
				'ver' 		 => SHAREPRINTS_VERSION,
				'in_footer' => true 
			),

		);
		
		foreach( $scripts as $script ){
			wp_register_script( $script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer'] );
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
			// Front End
			array(
				'handle' 		=> 'shareprints_lightbox',
				'object_name' 	=> 'shareprintsAjax',
				'l10n' 			=> array( 
					'url'				=> admin_url( 'admin-ajax.php' ),
					'ajax_nonce' 		=> wp_create_nonce('check-and-post-comments'),
					'name_required' 	=> __("Name is required",'shareprints'),
					'email_required' 	=> __("Email is required",'shareprints'),
					'email_invalid' 	=> __("Entered email is invalid",'shareprints'),
					'url_invalid' 		=> __("Entered website is invalid",'shareprints'),
					'comment_empty' 	=> __("Comment cannot be empty",'shareprints'),
					'unknown_error' 	=> __("Unknown Error. Please refresh the page.",'shareprints'),
					'image_caption' 	=> __("Image caption:",'shareprints'),
					
				)
			),

/*
			// Back End
			array(
				'handle' 		=> 'shareprints-images-script',
				'object_name' 	=> 'shareprints_images_l10n',
				'l10n' 			=> array( 
					'select'		=>	"SharePrints - ".__("Add Images",'shareprints'),
					'edit'			=>	"SharePrints - ".__("Edit Image",'shareprints'),
					'update'		=>	__("Update Image",'shareprints'),
					'uploadedTo'	=>	__("uploaded to this post",'shareprints'),
					'count_0'		=>	__("No images selected",'shareprints'),
					'count_1'		=>	__("1 image selected",'shareprints'),
					'count_2'		=>	__("%d images selected",'shareprints'),
					'expand_details'		=>	__("Expand Details",'shareprints'),
					'collapse_details'		=>	__("Collapse Details",'shareprints'),
					'url' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce('check-for-thumbs'),
					'save_update_nonce' => wp_create_nonce( 'shareprints-gallery-images' ),
					'add_modal_title' => "SharePrints - ".__("Add New Gallery",'shareprints'),
					'edit_modal_title' => "SharePrints - ".__("Edit Gallery",'shareprints'),
				)
			),

			array(
				'handle' 		=> 'shareprints-mce-modal-script',
				'object_name' 	=> 'shareprintsAjax',
				'l10n' 			=> array( 
									'url' => admin_url( 'admin-ajax.php' ),
									'ajax_nonce' => wp_create_nonce('shareprints-mce-modal-actions'),
									'assetpath' => SHAREPRINTS_PLUGIN_URL . 'assets/',
							   )
			),
*/

		);

		foreach( $scripts as $script ){
			wp_localize_script( $script['handle'], $script['object_name'], $script['l10n'] );
		}

	}

	/**
	 *
	 * enqueue_scripts_and_styles()
	 *
	 * Enqueue Scripts and Styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts_and_styles(){
		wp_enqueue_style('shareprints-fe-style');
	}


// Image Sizes
	/**
	 *
	 * shareprints_image_sizes()
	 *
	 * Defines SharePrints Image Sizes
	 *
	 * @since 1.0.0
	 */
	public function shareprints_image_sizes(){
		
		$image_sizes = array(

			'shareprints_thumb' => array(
				'width' => 100,
				'height'=> 100,
				'crop'  => true
			),
			'shareprints_160' => array(
				'width' => 160,
				'height'=> 9999,
				'crop' 	=> false
			),
			'shareprints_320' => array(
				'width' => 320,
				'height'=> 9999,
				'crop' 	=> false
			),
			'shareprints_480' => array(
				'width' => 480,
				'height'=> 9999,
				'crop' 	=> false
			),
			'shareprints_640' => array(
				'width' => 640,
				'height'=> 9999,
				'crop' 	=> false
			),
/*
			'shareprints_800' => array(
				'width' => 800,
				'height'=> 9999,
				'crop' 	=> false
			),
*/
			'shareprints_960' => array(
				'width' => 960,
				'height'=> 9999,
				'crop' 	=> false
			),
			'shareprints_1120' => array(
				'width' => 1120,
				'height'=> 9999,
				'crop' 	=> false
			),
/*
			'shareprints_1280' => array(
				'width' => 1280,
				'height'=> 9999,
				'crop' 	=> false
			),
*/
/*
			'shareprints_1920' => array(
				'width' => 1920,
				'height'=> 9999,
				'crop' 	=> false
			),
*/
		);
		
		return $image_sizes;
		
		//  multiples of 160
		//  160 320 480 640 800 960 1120 1280 1440 1600 1760 1920

		//   80 160 240 320 400 480  560  640  720  800  880  960

		//  160 320 640 1280 2560
		
		//   60 120 240  480 960
		
		//  max pixel area for PDF (.25" margins) 
		//  Letter 8.5" x 11" - max printable area = 8" x 10.5" @ 300DPI = 2400 x 3150px.  Halved = 1200 x 1575px
		//  A4 8.3" x 11.7" - max printable area = 7.8" x 11.2" @ 300DPI = 2340 x 3360px.  Halved = 1170 x 1680px

		//  Image size pixel conversions @ 300dpi -		
		//  2.5 x 3.5	-  750px * 1050px  (Wallet Size)
		//  3.5 X 5		- 1050px * 1500px  (Called "9 × 13 cm" worldwide)
		//  4 X 4 		- 1200px * 1200px
		//  4 X 6 		- 1200px * 1800px  (Called "10 × 15 cm" worldwide)
		//  5 X 7 		- 1500px * 2100px  (Called "13 × 18 cm" worldwide)
		//  8 X 10 		- 2400px * 3000px  (Called "20 × 25 cm" worldwide) */
		
	}

	/**
	 *
	 * add_shareprints_image_sizes()
	 *
	 * Adds SharePrints Image Sizes
	 *
	 * @since 1.0.0
	 */
	private function add_shareprints_image_sizes(){
		
		$image_sizes = $this->shareprints_image_sizes();
		
		foreach( $image_sizes as $key => $value ){
			
			add_image_size($key, $value['width'], $value['height'], $value['crop']);

		}
			
	}

	/**
	 *
	 * shareprints_header_output()
	 *
	 * Header output. Places a meta tag (viewport) in the header
	 *
	 * @since 1.0.0
	 */
	function shareprints_header_output(){
		echo '<!-- '.__('Below added by Shareprints Gallery','shareprints').' -->';
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui, user-scalable=0">';
		echo '<!-- '.__('Above added by Shareprints Gallery','shareprints').' -->';
	}


// Gallery Output
	/**
	 *
	 * load_gallery_defaults()
	 *
	 * Loads gallery defaults
	 *
	 * @param	$gallery	{array}
	 * @return	$gallery	{array}
	 *
	 * @since 1.0.0
	 */
	function load_gallery_defaults( $gallery ){

		// validate $gallery
		if( !is_array($gallery) )
		$gallery = array();
		
		// gallery specific defaults
		$gallery = apply_filters('shareprints/load_gallery_defaults/gallery_type=' . $gallery['gallery_type'] , $gallery);
		
		// return
		return $gallery;

	}

	/**
	 *
	 * create_gallery()
	 *
	 * Outputs a gallery
	 *
	 * @param	$gallery	{array}
	 * @return	$gallery	{array}
	 *
	 * @since 1.0.0
	 */
	function create_gallery( $gallery ){

		$gallery = apply_filters('shareprints/load_gallery_defaults', $gallery);

		// load styles
		do_action('shareprints/enqueue_gallery_styles/gallery_type=' . $gallery['gallery_type'], $gallery);

		// create gallery specific html
		do_action('shareprints/create_gallery/gallery_type=' . $gallery['gallery_type'], $gallery);

		// load scripts
		do_action('shareprints/enqueue_gallery_scripts/gallery_type=' . $gallery['gallery_type'], $gallery);

	}


// helpers
	/**
	 *
	 * get_current_page_url()
	 *
	 * returns the current page url
	 *
	 * @since 1.0.0
	 */
	public function get_current_page_url() {
		$pageURL = 'http';
		if( isset($_SERVER["HTTPS"]) ) {
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	/**
	 *
	 * failureRedirect()
	 *
	 * redirects to previous page, or homepage on failure.
	 *
	 * @since 1.0.0
	 */
	function failureRedirect($referer) {

		if ( $referer && $referer !== get_home_url().$_SERVER['REQUEST_URI'] ){
		    wp_safe_redirect( $referer );
		    exit();
		}else{
		    wp_safe_redirect( get_home_url() );
		    exit();
		}	
		
	}

}