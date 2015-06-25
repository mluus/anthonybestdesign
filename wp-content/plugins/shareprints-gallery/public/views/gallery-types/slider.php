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
class Slider_Gallery extends Shareprints_Gallery {
	
	/**
	 * __construct()
	 * 
	 * Set name / label needed for shortcode output, actions / filters
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		// vars
		$this->name = 'slider';
		$this->label = __("Slider",'shareprints');
		$this->defaults = array(
			'sizes' => array(
				'small'  => 'shareprints_320',
				'medium' => 'shareprints_480',
				'large'  => 'shareprints_640',
				'xlarge' => 'shareprints_960',
			),
			'retina_sizes' => array(
				'small'  => 'shareprints_640',
				'medium' => 'shareprints_960',
				'large'  => 'shareprints_1280',
				'xlarge' => 'shareprints_1920',
			),
			'width' => array(
				'small'  => 420,
				'medium' => 600,
				'large'  => 780,
				'xlarge' => 960,
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
		unset($g['gallery_images']);
		$startHeight = $images[0]['sizes'][$g['sizes'][$g['image_size']].'-height'];
		echo '<div class="shareprints_gallery slider '.$g['theme'].' pad_'.$g['image_padding'].' '.$g['image_size'].'" data-gallery="'.htmlspecialchars(json_encode($g), ENT_QUOTES, get_bloginfo( 'charset' )).'">';
			echo '<div id="slider_'.$g['gallery_id'].'" class="shareprints_flexslider" style="height:'.$startHeight.'px;">';
				require(SHAREPRINTS_PLUGIN_DIR . '/public/views/gallery-types/common.php');				
			echo '</div>';
		echo '</div>';
	}

}
new Slider_Gallery();