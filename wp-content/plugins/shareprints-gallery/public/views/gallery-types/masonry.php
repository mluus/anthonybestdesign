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
class Masonry_Gallery extends Shareprints_Gallery {
	
	/**
	 * __construct()
	 * 
	 * Set name / label needed for shortcode output, actions / filters
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		// vars
		$this->name = 'masonry';
		$this->label = __("Masonry",'shareprints');
		$this->defaults = array(
			'sizes' => array(
				'small'  => 'shareprints_160',
				'medium' => 'shareprints_320',
				'large'  => 'shareprints_480',
				'xlarge' => 'shareprints_640',
			),
			'retina_sizes' => array(
				'small'  => 'shareprints_320',
				'medium' => 'shareprints_640',
				'large'  => 'shareprints_960',
				'xlarge' => 'shareprints_1280',
			),
			'width'	=> array(
				'small'  => 160,
				'medium' => 320,
				'large'  => 480,
				'xlarge' => 640,
			),
		);
		$this->l10n = array();
		
		// do not delete!
    	parent::__construct();
	}
	
	/**
	 *
	 * enqueue_gallery_styles()
	 *
	 * Enqueues gallery specific styles
	 *
	 * @since 1.0.0
	 */
	function enqueue_gallery_styles(){}
	
	/**
	 *
	 * enqueue_gallery_scripts()
	 *
	 * Enqueues gallery specific scripts
	 *
	 * @since 1.0.0
	 */
	function enqueue_gallery_scripts(){
		wp_enqueue_script('shareprints_js_plugins');
		wp_enqueue_script('shareprints_loader');
	}

	/**
	 * 
	 * create_gallery()
	 * 
	 * Outputs the markup for the gallery
	 * 
	 * @param $g - array holding entire gallery object
	 *
	 * @since 1.0.0
	 */
	function create_gallery( $g ){
		$images = $g['gallery_images'];
		$pad_adjuster = array(
			'small'  => 1,
			'medium' => 1,
			'large'  => 2,
			'xlarge' => 2,
		);
		unset($g['gallery_images']);
		$g['image_padding'] = ( $g['image_padding'] * $pad_adjuster[$g['image_size']] );
		$g['width'] = $g['width'][$g['image_size']];
		
		echo '<div class="shareprints_gallery sp_masonry '.$g['image_size'].' pad_'.$g['image_padding'].'" data-gallery="'.htmlspecialchars(json_encode($g), ENT_QUOTES, get_bloginfo( 'charset' )).'">';
			require(SHAREPRINTS_PLUGIN_DIR . '/public/views/gallery-types/common.php');				
		echo '</div>';
				
	}

}
new Masonry_Gallery();