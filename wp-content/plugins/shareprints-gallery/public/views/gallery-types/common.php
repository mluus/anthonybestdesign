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
			echo '<div class="shareprints">';
				
			$order = -1;
			
			foreach( $images as $i ){
			
				$order++;
			
				$src = 'src="'.$i['sizes'][$g['sizes'][$g['image_size']]].'"';
				
				$width = $i['sizes'][$g['sizes'][$g['image_size']].'-width'];
				$height = $i['sizes'][$g['sizes'][$g['image_size']].'-height'];
				
				// Use Retina Images for galleries and images that support it
				if(isset($g['retina_sizes']) && $g['retina_sizes'][$g['image_size']] && isset($i['sizes'][$g['retina_sizes'][$g['image_size']]]))
				$src .=' srcset="'.$i['sizes'][$g['retina_sizes'][$g['image_size']]].' 2x"';
			
				$i_data = array(
				
					'image_id'		=> $i['id'],
					'order'			=> $order,					
					'height'		=> $height,//$i['sizes'][$g['sizes'][$g['image_size']].'-height'],
					'width'			=> $width,//$i['sizes'][$g['sizes'][$g['image_size']].'-width'],
					'orientation'	=> ( $width >= $height  ) ? ( $width === $height  ? 'square' : 'landscape' ) : 'portrait',
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
		
						echo '<img '.$src.' alt="'.$i_alt.'" class="'.$i_data['orientation'].'" draggable="false">';

					echo '</div>';
				
				echo '</div>';
				
			}
		
			echo '</div>';