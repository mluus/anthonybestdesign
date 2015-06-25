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

		echo '<div id="shareprints_settings" class="wrap shareprints-wrap">';
			echo '<h2 class="shareprints_settings_title">'.__( 'SharePrints Settings', 'shareprints' ).'</h2>';
	
			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'welcome';
			
			echo '<h2 class="nav-tab-wrapper">';
				echo '<a href="?post_type=shareprints&page=shareprints_settings&tab=welcome" class="nav-tab '.( ($active_tab == 'welcome') ? 'nav-tab-active' : '').'" title="'.__('Welcome', 'shareprints').'">'.__( 'Welcome', 'shareprints' ).'</a>';
				echo '<a href="?post_type=shareprints&page=shareprints_settings&tab=license" class="nav-tab '.( ($active_tab == 'license') ? 'nav-tab-active' : '').'" title="'.__('License', 'shareprints').'">'.__( 'License', 'shareprints' ).'</a>';
				echo '<a href="?post_type=shareprints&page=shareprints_settings&tab=how_to" class="nav-tab '.( ($active_tab == 'how_to') ? 'nav-tab-active' : '').'" title="'.__('How-Tos', 'shareprints').'">'.__( 'How-Tos', 'shareprints' ).'</a>';
				//echo '<a href="?post_type=shareprints&page=shareprints_settings&tab=add_ons" class="nav-tab '.( ($active_tab == 'add_ons') ? 'nav-tab-active' : '').'" title="'.__('Add-Ons', 'shareprints').'">'.__( 'Add Ons', 'shareprints' ).'</a>';
				echo '<a href="?post_type=shareprints&page=shareprints_settings&tab=support" class="nav-tab '.( ($active_tab == 'support') ? 'nav-tab-active' : '').'" title="'.__('Support', 'shareprints').'">'.__( 'Support', 'shareprints' ).'</a>';
				echo '<a href="?post_type=shareprints&page=shareprints_settings&tab=feedback" class="nav-tab '.( ($active_tab == 'feedback') ? 'nav-tab-active' : '').'" title="'.__('Feedback', 'shareprints').'">'.__( 'Feedback', 'shareprints' ).'</a>';
			echo '</h2>';

			echo '<form method="post" action="options.php">';

			if( $active_tab == 'welcome' ) {
				
				echo '<section class="shareprints_settings_section">';
				
					echo '<header>';		
						echo '<h2>'.__( 'Welcome', 'shareprints' ).'</h2>';
					echo '</header>';	

					echo '<article class="welcome-video fluid-container clearfix">';
				
						echo '<div class="content">';
							echo '<h3>'.__( 'Thank you for choosing SharePrints by FreakPlugins!', 'shareprints' ).'</h3>';					
							echo '<p><strong>'.__( 'Activate your copy of SharePrints now to receive support and updates.', 'shareprints' ).'</strong><br> '.__( 'Go to the', 'shareprints' ).' <a href="?post_type=shareprints&page=shareprints_settings&tab=license">'.__('License', 'shareprints' ).'</a> '.__('tab and enter the license key you received via email after you purchased the plugin.', 'shareprints' ).'</p>';

			
							echo '<h3>'.__( 'Getting Started:', 'shareprints' ).'</h3>';
							echo '<p>'.__('Watch the video to learn how to create your first gallery!','shareprints').'</p>';
							echo '<p>'.__( 'Go to the', 'shareprints' ).' <a href="?post_type=shareprints&page=shareprints_settings&tab=how_to">'.__('How-Tos', 'shareprints' ).'</a> '.__('tab to see the complete video guide to using SharePrints.', 'shareprints' ).'</p>';				
							
						echo '</div>';
				
						echo '<div class="media"><div class="holder"><iframe width="465" height="262" src="//www.youtube.com/embed/twXVaHERX24?modestbranding=1&rel=0&color=white&showinfo=0&theme=light&autohide=1" frameborder="0" allowfullscreen></iframe></div></div>';
				
					echo '</article>';
				echo '</section>';		
				
			}
			
			if( $active_tab == 'license' ) {
				echo '<section class="shareprints_settings_section">';	
					settings_fields('shareprints_settings');
					$license 	= get_option( 'shareprints_license_key' );
					$status 	= get_option( 'shareprints_license_status' );
				
					echo '<header>';
						echo '<h2 class="shareprints_settings_subsection">'.__( 'License', 'shareprints' ).'</h2>';
					echo '</header>';

					echo '<p>'.__( 'Please enter the license key that was sent to you via email after your purchased SharePrints.', 'shareprints' ).'</p>';
					
					echo '<p>'.__( 'You can also find your license key by logging into your account at', 'shareprints' ).' <a href="http://freakplugins.com/account/" target="_blank">'.__('FreakPlugins.com.', 'shareprints' ).'</a></p>';
					
					echo '<p><strong>'.__('Activating your license allows you to receive support and updates.', 'shareprints' ).'</strong></p>';

					echo '<div class="shareprints_license_field">';
						echo '<label for="shareprints_license_key">'.__('License Key', 'shareprints');
							$class = $status !== 'valid' ? $status !== 'deactivated' ? 'error' : '' : 'valid';
							wp_nonce_field( 'shareprints-license-nonce', 'shareprints-license-nonce' );
							echo '<input id="shareprints_license_key" name="shareprints_license_key" type="text" class="regular-text '.$class.'" value="'.esc_attr( $license ).'" />';
							if($status && $status === 'valid')
							echo '<input type="submit" class="button-secondary" name="shareprints_license_deactivate" value="'.__('Deactivate License', 'shareprints').'"/>';
							if($status && $status === 'deactivated')
							echo '<input type="submit" class="button-secondary" name="shareprints_license_activate" value="'.__('Activate License', 'shareprints').'"/>';
						echo '</label>';
					echo '</div>';
					
					
					
					
					if(has_action('shareprints/additional_license_fields')){
						
						echo '<div class="shareprints_addons_licenses">';
						
							echo '<h3>'.__( 'Add-on License Keys', 'shareprints' ).'</h3>';
						
							do_action('shareprints/additional_license_fields');					
						
						echo '</div>';
					
					}
					
					submit_button();

				echo '</section>';	
			}

			if( $active_tab == 'how_to' ) {
				
				echo '<section class="shareprints_settings_section">';		

					echo '<header>';
					
						echo '<h2>'.__( 'SharePrints How-Tos', 'shareprints' ).'</h2>';
					
						echo '<p>'.__( 'A video guide to using SharePrints.', 'shareprints' ).'</p>';
					
					echo '</header>';
				
					$topics = array(
					
						array(
							'title' 		=> __( 'Creating Galleries', 'shareprints' ),
							'video_src' 	=> 'twXVaHERX24',
							'description' 	=> __( 'SharePrints makes it easy to create galleries from anyplace on your WordPress site. This how-to video shows how you can create galleries directly from a page or post edit screen without having to refresh or open a new browser window.', 'shareprints' ),
						),
						array(
							'title' 		=> __( 'Adding Galleries to Pages or Posts', 'shareprints' ),
							'video_src' 	=> 'SpvWuJJG_rw',
							'description' 	=> __( 'Say goodbye to shortcodes! SharePrints was designed to look and feel like a natural part of WordPress. This how-to video shows how to add galleries to a page or post, and how visual shortcodes make working with galleries in the WordPress editor simple.', 'shareprints' ),
						),
						array(
							'title' 		=> __( 'Editing Galleries', 'shareprints' ),
							'video_src' 	=> 'eDjJRvBZt2U',
							'description' 	=> __( 'SharePrints is all about smart workflow. Making changes to a gallery can be done directly from the page or post it’s on! This how-to video shows how you can edit galleries from a page or post edit screen without having to refresh or open a new browser window.', 'shareprints' ),
						),
						array(
							'title' 		=> __( 'Editing Gallery Images', 'shareprints' ),
							'video_src' 	=> 'oL7ttACo2XM',
							'description' 	=> __( 'Do you want to add or edit an image’s title, caption, description or alt text? You could search through your entire media library until you find the image, but that’s a pain. This how-to video shows how you can edit gallery images directly from a page or post edit screen without having to refresh or open a new browser window.', 'shareprints' ),
						),
						array(
							'title' 		=> __( 'Customizing Gallery Settings', 'shareprints' ),
							'video_src' 	=> 'pMPc4ltDwQc',
							'description' 	=> __( 'With powerful settings that are easy to understand, SharePrints makes it nearly impossible to produce bad results. This how-to video explains the various gallery settings you can use to customize the look and feel of your galleries.', 'shareprints' ),
						),
						array(
							'title' 		=> __( 'Customizing Lightbox Settings', 'shareprints' ),
							'video_src' 	=> 'ZOKQ8sPFito',
							'description' 	=> __( 'The SharePrints lightbox is a powerful tool that allows your visitors to interact with your content in new and interesting ways. This how-to video explains the various lightbox settings you can use to customize the look and feel of the lightbox.', 'shareprints' ),
						),
						array(
							'title' 		=> __( 'Saving and Loading Settings', 'shareprints' ),
							'video_src' 	=> '9KSTGe0MEK8',
							'description' 	=> __( 'SharePrints utilizes smart workflows. A great example is the ability to save and load gallery and lightbox settings. Saved settings make it easy to achieve a consistent, custom appearance across multiple galleries. This how-to video shows how you can save, load, and update your favorite settings.', 'shareprints' ),
						),

/*
						array(
							'title' 		=> __( '', 'shareprints' ),
							'video_src' 	=> '',
							'description' 	=> '',
							'parts' => array(
								array(
									'topic' => 'Topic',
									'time' => '0:00',
								),
							),
						),
*/
						
					);
					
					// Table of contents
					echo '<div id="how_to_table_contents">';	
						
						echo '<h3>'.__( 'Table of contents', 'shareprints' ).'</h3>';
						
						echo '<ol>';
						
							foreach($topics as $topic):
						
								echo '<li><a href="#'.sanitize_title_with_dashes($topic['title']).'" >'.$topic['title'].'</a></li>';
						
							endforeach;
						
						echo '</ol>';
					
					echo '</div>';
				
					// Content Sections
/*
					foreach($topics as $topic):
						
						echo '<div id="'.sanitize_title_with_dashes($topic['title']).'" class="how_to_section">';
							
							
							echo '<div class="how_to_video_info">';
							
								echo '<h3>'.$topic['title'].'</h3>';
								
								echo '<div class="how_to_description">';
								
									echo apply_filters('the_content', $topic['description']);
								
								echo '</div>';
								
								$parts = isset($topic['parts']) && $topic['parts'] !== '' ? $topic['parts'] : false;
								
								if($parts):
	
									echo '<div class="how_to_parts">';
									
										echo '<h4>'.__( 'In this video:', 'shareprints' ).'</h4>';
									
										echo '<ul>';
									
										foreach($parts as $part):
										
											echo '<li>'.$part['topic'].' <span>'.$part['time'].'</span></li>';
										
										endforeach;
										
										echo '</ul>';
									
									echo '</div>';
	
								endif;

							echo '</div>';
							
							echo '<div class="how_to_video">';
						
								//echo '<iframe src="'.$topic['video_src'].'" width="650" height="396" frameborder="0"></iframe>';


								echo '<iframe width="650" height="366" src="//www.youtube.com/embed/'.$topic['video_src'].'?modestbranding=1&rel=0&color=white&showinfo=0&theme=light&autohide=1" frameborder="0" allowfullscreen></iframe>';


						
							echo '</div>';
						
						echo '</div>';	
					
					endforeach;
*/
					foreach($topics as $topic):

						echo '<article id="'.sanitize_title_with_dashes($topic['title']).'" class="fluid-container clearfix">';
					
							echo '<div class="content"><h3>'.$topic['title'].'</h3>'.apply_filters('the_content', $topic['description']).'</div>';
					
							echo '<div class="media"><div class="holder"><iframe width="465" height="262" src="//www.youtube.com/embed/'.$topic['video_src'].'?modestbranding=1&rel=0&color=white&showinfo=0&theme=light&autohide=1" frameborder="0" allowfullscreen></iframe></div></div>';
					
						echo '</article>';
				
					endforeach;

				echo '</section>';		
				
			}

			if( $active_tab == 'add_ons' ) {
				
				echo '<section class="shareprints_settings_section">';		
				
					echo '<header>';
					
						echo '<h2>'.__( 'Add-Ons', 'shareprints' ).'</h2>';
						
						echo '<p>'.__('Below are Add Ons for SharePrints created by Freak Plugins', 'shareprints').'</p>';
					
					echo '</header>';
				
				echo '</section>';		
				
			}

			if( $active_tab == 'support' ) {
								
				echo '<section class="shareprints_settings_section">';
				
					echo '<header>';
					
						echo '<h2>'.__( 'Support', 'shareprints' ).'</h2>';
						
					echo '</header>';

					echo '<p>'.__( 'Do you need help with SharePrints? No problem.', 'shareprints' ).'</p>';
					
					echo '<p>'.__('You can access our support forums and priority email support by logging in to your account at ', 'shareprints' ).' <a href="https://freakplugins.com/account/" target="_blank">'.__('FreakPlugins.com.', 'shareprints' ).'</a></p>';
				
				echo '</section>';		
								
			}

			if( $active_tab == 'feedback' ) {
								
				echo '<section class="shareprints_settings_section">';		
				
					echo '<header>';
					
						echo '<h2>'.__( 'Feedback', 'shareprints' ).'</h2>';

					echo '</header>';

					echo '<p>'.__('Do you have feedback on SharePrints or an idea for a new feature or add on?','shareprints').'</p>';
					echo '<p>'.__('Tweet us at ','shareprints').'<a href="https://twitter.com/FreakPlugins" target="_blank">'.__('@FreakPlugins', 'shareprints' ).'</a>'.__(' or visit ','shareprints').'<a href="http://freakplugins.com/contact/" target="_blank">'.__('FreakPlugins.com', 'shareprints' ).'</a>'.__(' to share your thoughts!','shareprints').'</p>';
				
				echo '</section>';		
								
			}
			
			echo '</form>';

		echo '</div>';