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
class Slidescroll_Gallery extends Shareprints_Gallery {
	
	/**
	 * __construct()
	 * 
	 * Set name / label needed for shortcode output, actions / filters
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		// vars
		$this->name = 'slidescroll';
		$this->label = __("SlideScroll",'shareprints');
		$this->defaults = array(
			'sizes' => array(
				'small'  => 'shareprints_320',
				'medium' => 'shareprints_480',
				'large'  => 'shareprints_640',
				'xlarge' => 'shareprints_960',
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
		wp_enqueue_script('jquery-ui-slider');
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

		$s_id = 0;
		$s_height = 100000;
		
		echo '<div class="shareprints_gallery slidescroll '.$g['theme'].' pad_'.$g['image_padding'].' '.$g['image_size'].'" data-gallery="'.htmlspecialchars(json_encode($g), ENT_QUOTES, get_bloginfo( 'charset' )).'">';
			
			echo '<div class="shareprints">';
			$order = -1;
			foreach( $images as $i ){
				$order++;
				$orientation = $i['orientation'];
				switch($orientation){
					case "portrait":
						switch($g['image_size']){
							case "small":
								$size = 'shareprints_160';
								
							break;
							case "medium":
								$size = 'shareprints_160';
							break;
							case "large":
								$size = $g['sizes']['small'];
							break;
							case "xlarge":
								$size = $g['sizes']['medium'];
							break;
						}
					break;
					case "square":
						switch($g['image_size']){
							case "small":
								$size = $g['sizes']['small'];
							break;
							case "medium":
								$size = $g['sizes']['small'];
							break;
							case "large":
								$size = $g['sizes']['medium'];
							break;
							case "xlarge":
								$size = $g['sizes']['large'];
							break;
						}
					break;
					default:
						$size = $g['sizes'][$g['image_size']];
				}	
				$src = 'src="'.$i['sizes'][ $size ].'"';
				$width = $i['sizes'][ $size.'-width'];
				$height = $i['sizes'][ $size.'-height'];
				if($height < $s_height){
					$s_id = $i['id'];
					$s_height = $height;
					$s_width = $width;
				}
				// Use Retina Images for galleries and images that support it
				//if(isset($g['retina_sizes']) && $g['retina_sizes'][$g['image_size']] && isset($i['sizes'][$g['retina_sizes'][$g['image_size']]]))
				//$src .=' srcset="'.$i['sizes'][$g['retina_sizes'][$g['image_size']]].' 2x"';
				$i_data = array(
					'image_id'		=> $i['id'],
					'order'			=> $order,					
					'height'		=> $height,//$i['sizes'][$g['sizes'][$g['image_size']].'-height'],
					'width'			=> $width,//$i['sizes'][$g['sizes'][$g['image_size']].'-width'],
					'orientation'	=> $orientation,
					'large'			=> $i['sizes']['shareprints_1120'],
					'medium'		=> $i['sizes']['shareprints_640'],
					'mediumSmall'	=> $i['sizes']['shareprints_480'],
					'small'			=> $i['sizes']['shareprints_320'],
					'thumb'			=> $i['sizes']['shareprints_thumb'],
					'canComment'	=> comments_open($i['id']),
					'commentCount'	=> wp_count_comments($i['id'])->approved,
					'title'			=> $g['titles'] ? $i['title'] : false,
					'caption'		=> $g['captions'] ? $i['caption'] : false,
					'description'	=> $g['descriptions'] ? apply_filters('the_content', $i['description']) : false,
				);
				$i_alt = ( $i['alt'] && $i['alt'] !== '' ) ? $i['alt'] : __('Gallery Image','shareprints');
				echo '<div id="image_'.$i['id'].'" class="shareprints_li'.$g['image_hover'].'">';
					echo '<div class="shareprints_thumb" data-image="'.htmlspecialchars(json_encode($i_data), ENT_QUOTES, get_bloginfo( 'charset' )).'">'; 
						echo '<img '.$src.' alt="'.$i_alt.'" class="'.$orientation.'" draggable="false">';
					echo '</div>';
				echo '</div>';
			}
			echo '</div>';			
			
			echo '<span class="sizer" data-id="'.$s_id.'" data-height="'.$s_height.'"></span>';// 

			echo '<div class="sp_ui_slider_wrapper">';
				echo '<span class="sp_ui_slider_start"></span>';
				echo '<span class="sp_ui_slider_end"></span>';
				echo '<div class="sp_ui_slider"></div>';
			echo '</div>';

		echo '</div>';
		
	}

}

new Slidescroll_Gallery();