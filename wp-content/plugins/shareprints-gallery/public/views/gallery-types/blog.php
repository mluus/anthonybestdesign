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
class Blog_Gallery extends Shareprints_Gallery {
	
	/**
	 * __construct()
	 * 
	 * Set name / label needed for shortcode output, actions / filters
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		// vars
		$this->name = 'blog';
		$this->label = __("BlogStyle",'shareprints');
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
		//wp_enqueue_script('shareprints_loader');		
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
		
		echo '<div class="shareprints_gallery blog_style '.$g['image_size'].' pad_'.$g['image_padding'].'" data-gallery="'.htmlspecialchars(json_encode($g), ENT_QUOTES, get_bloginfo( 'charset' )).'">';
			echo '<div class="shareprints">';
			$order = -1;
			
			foreach( $images as $i ){
				$order++;
				$i_data = array(
					'image_id'		=> $i['id'],
					'order'			=> $order,
					'height'		=> $i['sizes'][$g['sizes'][$g['image_size']].'-height'],
					'width'			=> $i['sizes'][$g['sizes'][$g['image_size']].'-width'],
					'orientation'	=> $i['orientation'],//( $i['width'] >= $i['height']  ) ? ( $i['width'] == $i['height']  ) ? 'square' : 'landscape' : 'portrait',
					'large'			=> $i['sizes']['shareprints_1120'],
					'medium'		=> $i['sizes']['shareprints_640'],
					'mediumSmall'	=> $i['sizes']['shareprints_480'],
					'small'			=> $i['sizes']['shareprints_320'],
					'thumb'			=> $i['sizes']['shareprints_thumb'],
					'canComment'	=> comments_open($i['id']),
					'commentCount'	=> wp_count_comments($i['id'])->approved,
					'title'			=> $i['title'],
					'caption'		=> $i['caption'],
					'description'	=> $i['description'],
				);
				$i_alt = ( $i['alt'] && $i['alt'] !== '' ) ? $i['alt'] : __('Gallery Image','shareprints');

				echo '<div class="blog_article">';

					echo '<div id="image_'.$i['id'].'" class="shareprints_li'.$g['image_hover'].'">';
						
						echo '<div class="shareprints_thumb"  data-image="'.htmlspecialchars(json_encode($i_data), ENT_QUOTES, get_bloginfo( 'charset' )).'">';  
							echo '<img src="'.$i['sizes'][$g['sizes'][$g['image_size']]].'" alt="'.$i_alt.'">';
						echo '</div>';
						
						if( $i['caption'] && $i['title'] && $i['description'] ) 
						echo '<div class="shareprints_image_caption"><p>'.$i['caption'].'</p></div>';
						
					echo '</div>';
					
					echo '<div class="blog_content">';
						
						if( $i['title'] )
						echo '<h4 class="shareprints_image_title">'.$i['title'].'</h4>';
						
						if( $i['description'] ){
							echo '<div class="shareprints_image_description">'.apply_filters('the_content', $i['description']).'</div>';
						}else{
							if( $i['caption'] )
							echo '<div class="shareprints_image_caption"><p>'.$i['caption'].'</p></div>';
						}
					
					echo '</div>';
						
				echo '</div>';

			}
			
			echo '</div>';
		echo '</div>';

	}

}
new Blog_Gallery();