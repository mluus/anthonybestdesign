<?php
/**
 * Shareprints Gallery
 *
 * @package  Shareprints
 * @author  JR w/Freak Plugins <jr@freakplugins.com>
 * @license  GPLv3+
 * @link   http://freakplugins.com
 * @copyright Copyright (c) 2014 Freak Plugins, LLC - All Rights Reserved
 */
 
 //glyphicon glyphicon-&#63;tion-sign
 

// Primary Modal
echo '<div id="shareprints_mce_modal" class="fade sp_modal" tabindex="-1" role="dialog" aria-hidden="true">'; // aria-labelledby="shareprints_mce_modal_title" 
	echo '<div class="sp_modal-dialog">';
		echo '<div class="sp_modal-content">';
  	
			// Modal Header
			echo '<div class="sp_modal-header">';
				echo '<h4 class="sp_modal-title">'.__('SharePrints', 'shareprints').'</h4>';
			echo '</div>';

			// Modal Body
			echo '<div class="sp_modal-body">'; //row
			
				// Modal Tabs
				echo '<div class="sp_modal-nav">';
					echo '<ul class="nav nav-pills nav-stacked">';
						echo '<li class="active main"><a href="#gallery_select" title="'.__('Gallery Select', 'shareprints').'">'.__('Gallery Select', 'shareprints').'</a></li>';
						echo '<li><a href="#gallery_options" title="'.__('Gallery Settings', 'shareprints').'">'.__('Gallery Settings', 'shareprints').'</a></li>';
						echo '<li><a href="#lightbox_options" title="'.__('Lightbox Settings', 'shareprints').'">'.__('Lightbox Settings', 'shareprints').'</a></li>';
						echo '<li><a href="#save_load_settings" title="'.__('Save / Load', 'shareprints').'">'.__('Save / Load', 'shareprints').'</a></li>';
					echo '</ul>';
				echo '</div>'; // modal tabs
				
				// Modal Tab Content
				echo '<div class="tab-content form-horizontal">';
					
					// Alert Holder
					echo '<div class="sp_alert_holder"></div>';
					
					// Gallery Select
					echo '<div id="gallery_select" class="tab-pane active">';

						// Existing Galleries
						echo '<div class="form-group">';
							echo '<label class="control-label" for="gallery_id">'.__('All Galleries', 'shareprints').'</label>';
							echo '<div class="form-controls form-controls-special">';
								echo '<select id="gallery_id" name="gallery_id" class="form-control" size="7" data-error="'.__('No gallery selected', 'shareprints').'">';
									$args = array( 'post_type' => 'shareprints', 'posts_per_page'=>-1 );
									$shareprints = get_posts($args);
									foreach($shareprints as $shareprint){
										echo '<option value="'.$shareprint->ID.'">'.$shareprint->post_title.'</option>';
									}
								echo '</select>';
								
								
								echo '<div id="gallery_select_actions" class="btn-group btn-group-sm btn-group-justified">';
									echo '<a data-action="create" id="sp_create_gallery" class="btn btn-default" title="'.__('Add New Gallery','shareprints').'">'.__('Add New Gallery','shareprints').'</a>';
									echo '<a data-action="edit" id="sp_edit_gallery" class="btn btn-default'.(!$shareprints ? ' disabled' : '').'" title="'.__('Edit Gallery','shareprints').'">'.__('Edit Gallery','shareprints').'</a>';
									echo '<a data-action="delete" id="sp_delete_gallery" class="btn btn-danger'.(!$shareprints ? ' disabled' : '').'" title="'.__('Move to Trash','shareprints').'">'.__('Move to Trash','shareprints').'</a>';
								echo '</div>';
								
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Select the gallery you wish to insert. All published SharePrints galleries will appear in this list.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>'; // Existing Galleries

					echo '</div>'; // gallery select
					
					// Gallery Options
					echo '<div id="gallery_options" class="tab-pane">';
					
						// GALLERY TYPE
						echo '<div class="form-group">';
							echo '<label class="control-label" for="gallery_type">'.__('Gallery Type', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<select id="gallery_type" name="gallery_type" class="form-control">';
									$registered_galleries = apply_filters('shareprints/registered_galleries', array());
									foreach($registered_galleries as $k => $v){
										echo '<option value="'.$k.'">'.$v.'</option>';
									}
								echo '</select>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the display method for the gallery.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
						// GALLERY POSITION
						echo '<div class="form-group">';
							echo '<label class="control-label" for="gallery_position">'.__('Gallery Position', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default" for="gallery_position-pos_left"><input type="radio" name="gallery_position" id="gallery_position-pos_left" class="form-control" value="pos_left">'.__('Left', 'shareprints').'</label>';
									echo '<label class="btn btn-default active" for="gallery_position-pos_center"><input type="radio" name="gallery_position" id="gallery_position-pos_center" class="form-control" value="pos_center" checked="checked">'.__('Center', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="gallery_position-pos_right"><input type="radio" name="gallery_position" id="gallery_position-pos_right" class="form-control" value="pos_right">'.__('Right', 'shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the position for the gallery. Left/Right allows the gallery to be placed alongside other content (such as text). Center horizontally centers the gallery.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
						// GALLERY WIDTH
						echo '<div class="form-group">';
							echo '<label class="control-label" for="gallery_width">'.__('Gallery Width (%)', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default" for="gallery_width-width_20"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_20" value="width_20">20</label>';
									echo '<label class="btn btn-default" for="gallery_width-width_30"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_30" value="width_30">30</label>';
									echo '<label class="btn btn-default" for="gallery_width-width_40"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_40" value="width_40">40</label>';
									echo '<label class="btn btn-default" for="gallery_width-width_50"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_50" value="width_50">50</label>';
									echo '<label class="btn btn-default" for="gallery_width-width_60"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_60" value="width_60">60</label>';
									echo '<label class="btn btn-default" for="gallery_width-width_70"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_70" value="width_70">70</label>';
									echo '<label class="btn btn-default" for="gallery_width-width_80"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_80" value="width_80">80</label>';
									echo '<label class="btn btn-default" for="gallery_width-width_90"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_90" value="width_90">90</label>';
									echo '<label class="btn btn-default active" for="gallery_width-width_100"><input type="radio" class="form-control" name="gallery_width" id="gallery_width-width_100" value="width_100" checked="checked">100</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the width of the gallery. Width is relative to your theme\'s design.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
						// IMAGE SIZE
						echo '<div class="form-group">';
							echo '<label class="control-label" for="image_size">'.__('Image Size', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default active" for="image_size-small"><input type="radio" name="image_size" id="image_size-small" class="form-control" value="small" checked="checked">'.__('S', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_size-medium"><input type="radio" name="image_size" id="image_size-medium" class="form-control" value="medium">'.__('M', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_size-large"><input type="radio" name="image_size" id="image_size-large" class="form-control" value="large">'.__('L', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_size-xlarge"><input type="radio" name="image_size" id="image_size-xlarge" class="form-control" value="xlarge">'.__('XL', 'shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the display size for images in the gallery. Image sizes vary by Gallery Type.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
						// IMAGE PADDING
						echo '<div class="form-group">';
							echo '<label class="control-label" for="image_padding">'.__('Image Padding', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default active" for="image_padding-0"><input type="radio" class="form-control" name="image_padding" id="image_padding-0" value="0" checked="checked">'.__('0','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-1"><input type="radio" class="form-control" name="image_padding" id="image_padding-1" value="1">'.__('1','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-2"><input type="radio" class="form-control" name="image_padding" id="image_padding-2" value="2">'.__('2','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-3"><input type="radio" class="form-control" name="image_padding" id="image_padding-3" value="3">'.__('3','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-4"><input type="radio" class="form-control" name="image_padding" id="image_padding-4" value="4">'.__('4','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-5"><input type="radio" class="form-control" name="image_padding" id="image_padding-5" value="5">'.__('5','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-6"><input type="radio" class="form-control" name="image_padding" id="image_padding-6" value="6">'.__('6','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-7"><input type="radio" class="form-control" name="image_padding" id="image_padding-7" value="7">'.__('7','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-8"><input type="radio" class="form-control" name="image_padding" id="image_padding-8" value="8">'.__('8','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-9"><input type="radio" class="form-control" name="image_padding" id="image_padding-9" value="9">'.__('9','shareprints').'</label>';
									echo '<label class="btn btn-default" for="image_padding-10"><input type="radio" class="form-control" name="image_padding" id="image_padding-10" value="10">'.__('10','shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the padding around images in the gallery. The numbers represent a scale of padding, not actual pixels.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
						// COLOR THEME
						echo '<div class="form-group">';
							echo '<label class="control-label" for="theme">'.__('Color Theme', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default active" for="theme-dark"><input type="radio" class="form-control" name="theme" id="theme-dark" value="dark" checked="checked">'.__('Dark', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="theme-light"><input type="radio" class="form-control" name="theme" id="theme-light" value="light">'.__('Light', 'shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the gallery color theme. Color Theme applies to various gallery elements including Slider arrows, Filmstrip background, etc.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
					echo '</div>'; // GALLERY OPTIONS
				
					// Lightbox Options
					echo '<div id="lightbox_options" class="tab-pane">';

						// HOVER EFFECT
						echo '<div class="form-group">';
							echo '<label class="control-label" for="image_hover">'.__('Hover Effect', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<select id="image_hover" name="image_hover" class="form-control">';
									echo '<option value="false">'.__('None','shareprints').'</option>';
									echo '<optgroup label="'.__('Color Effects','shareprints').'">';
										echo '<option value="fadeoutblack">'.__('Fadeout - Black','shareprints').'</option>';
										echo '<option value="fadeoutwhite">'.__('Fadeout - White','shareprints').'</option>';
										echo '<option value="colortobw">'.__('Color - Full to B&W','shareprints').'</option>';
										echo '<option value="bwtocolor">'.__('Color - B&W to Full','shareprints').'</option>';
										echo '<option value="colorfullhalf">'.__('Color - Full to Half','shareprints').'</option>';
										echo '<option value="colorhalffull">'.__('Color - Half to Full','shareprints').'</option>';
										echo '<option value="sepiatocolor">'.__('Sepia to Color','shareprints').'</option>';
										echo '<option value="colortosepia">'.__('Color to Sepia','shareprints').'</option>';
										echo '<option value="invert">'.__('Invert Color','shareprints').'</option>';
										echo '<option value="vintage">'.__('Vintage Camera','shareprints').'</option>';
										echo '<option value="overexpose">'.__('Overexpose','shareprints').'</option>';
										echo '<option value="overexposemore">'.__('Overexpose more','shareprints').'</option>';
										echo '<option value="blur">'.__('Blur','shareprints').'</option>';
										echo '<option value="blurmore">'.__('Blur more','shareprints').'</option>';
									echo '</optgroup>';
									echo '<optgroup label="'.__('Motion Effects','shareprints').'">';
										echo '<option value="popout">'.__('Pop out','shareprints').'</option>';
										echo '<option value="popoutmore">'.__('Pop out more','shareprints').'</option>';
										echo '<option value="tilt">'.__('Tilt','shareprints').'</option>';
										echo '<option value="tiltmore">'.__('Tilt more','shareprints').'</option>';
										echo '<option value="shrink">'.__('Shrink','shareprints').'</option>';
										echo '<option value="shrinkmore">'.__('Shrink more','shareprints').'</option>';
										echo '<option value="zoomin">'.__('Zoom-in','shareprints').'</option>';
										echo '<option value="zoominmore">'.__('Zoom-in more','shareprints').'</option>';
										echo '<option value="zoomout">'.__('Zoom-out','shareprints').'</option>';
										echo '<option value="zoomoutmore">'.__('Zoom-out more','shareprints').'</option>';
									echo '</optgroup>';
								echo '</select>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the hover effect for gallery images that open the lightbox. This setting does not apply to images insde the lightbox. * Not supported in IE9/10; limited support in Firefox.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';

						// LIGHTBOX TYPE
						echo '<div class="form-group">';
							echo '<label class="control-label" for="lightbox_type">'.__('Lightbox Type', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default active" for="lightbox_type-slide"><input type="radio" class="form-control" name="lightbox_type" id="lightbox_type-slide" value="slide" checked="checked">'.__('Slider', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="lightbox_type-fade"><input type="radio" class="form-control" name="lightbox_type" id="lightbox_type-fade" value="fade">'.__('Fader', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="lightbox_type-false"><input type="radio" class="form-control" name="lightbox_type" id="lightbox_type-false" value="false">'.__('Disabled', 'shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the display method for the lightbox or Disable it. Slider / Fader controls the transition effect between images in the lightbox.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
				
						// IMAGE INFO
						echo '<div class="form-group">';
							echo '<label class="control-label">'.__('Image Info', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default active" for="titles"><input type="checkbox" class="form-control" id="titles" name="titles" checked="checked" value="true">'.__('Titles', 'shareprints').'</label>';
									echo '<label class="btn btn-default active" for="captions"><input type="checkbox" class="form-control" id="captions" name="captions" checked="checked" value="true">'.__('Captions', 'shareprints').'</label>';
									echo '<label class="btn btn-default active" for="descriptions"><input type="checkbox" class="form-control" id="descriptions" name="descriptions" checked="checked" value="true">'.__('Descriptions', 'shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Set the image attributes to be shown in the info panel.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
						// COMMENTS
						echo '<div class="form-group">';
							echo '<label class="control-label" for="comments">'.__('Image Comments', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default active" for="comments-true"><input type="radio" class="form-control" name="comments" id="comments-true" value="true" checked="checked">'.__('Enabled', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="comments-false"><input type="radio" class="form-control" name="comments" id="comments-false" value="false">'.__('Disabled', 'shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Enable or Disable the comments panel. SharePrints inherits your WordPress discussion settings.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
				
						// SOCIAL SHARING
						echo '<div class="form-group">';
							echo '<label class="control-label" for="sharing">'.__('Social Sharing', 'shareprints').'</label>';
							echo '<div class="form-controls">';
								echo '<div class="btn-group btn-group-justified" data-toggle="sp_buttons">';
									echo '<label class="btn btn-default active" for="sharing-true"><input type="radio" class="form-control" name="sharing" id="sharing-true" value="true" checked="checked">'.__('Enabled', 'shareprints').'</label>';
									echo '<label class="btn btn-default" for="sharing-false"><input type="radio" class="form-control" name="sharing" id="sharing-false" value="false">'.__('Disabled', 'shareprints').'</label>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Enable or Disable the sharing panel. If enabled, visitors can share a link with their social media followers.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
												
					echo '</div>';// Lightbox Options
					
					// Save / Load 
					echo '<div id="save_load_settings" class="tab-pane">';
						$favorites = get_option( 'shareprints_mce_favorites' ); // delete_option( 'shareprints_mce_favorites' ); // testing 
						echo '<div class="form-group">';
							
							echo '<label class="control-label">'.__('Save Settings', 'shareprints').'</label>';
							
							echo '<div class="form-controls">';
								echo '<div id="save_option" class="input-group">';
									echo '<input type="text" id="save_name" name="save_name" class="form-control" placeholder="'.__('Enter settings name here', 'shareprints').'" data-error="'.__('Enter settings name', 'shareprints').'">';
									echo '<span class="input-group-btn">';
										echo '<button id="sp_save" class="btn btn-default" type="button">'.__('Save Settings', 'shareprints').'</button>';
									echo '</span>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Give your settings a name and click Save. Overwrite existing saved settings by entering the same name.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label class="control-label">'.__('Load Settings', 'shareprints').'</label>';
							echo '<div class="form-controls form-controls-special">';
								echo '<select id="saved_settings" class="form-control" size="7" data-success="'.__('Saved settings successfully loaded.', 'shareprints').'" data-error="'.__('No settings selected', 'shareprints').'">';
									if($favorites){
										foreach($favorites as $k => $v){
											echo '<option value="'.$k.'" data-values="'.htmlspecialchars(json_encode($v), ENT_QUOTES, get_bloginfo( 'charset' )).'">'.stripslashes($v['save_name']).'</option>';
										}
									}
								echo '</select>';
								echo '<div id="saved_settings_actions" class="btn-group btn-group-sm btn-group-justified">';
									echo '<a data-action="load" id="sp_load" class="btn btn-default'.(!$favorites ? ' disabled' : '').'" title="'.__('Load Settings','shareprints').'">'.__('Load Settings','shareprints').'</a>';
									echo '<a data-action="delete" id="sp_delete" class="btn btn-danger'.(!$favorites ? ' disabled' : '').'" title="'.__('Delete Settings','shareprints').'">'.__('Delete Settings','shareprints').'</a>';
								echo '</div>';
							echo '</div>';
							echo '<div class="sp_help">';
								echo '<span title="'.__('Saved settings allow you to quickly reuse your favorite SharePrints setup with any gallery. Settings you have saved appear in this list.', 'shareprints').'">&#63;</span>';
							echo '</div>';
						echo '</div>';
					echo '</div>';// Save / Load 

				echo '</div>'; // Modal Tab Content

			echo '</div>'; // modal body
			
			// Modal Footer
			echo '<div class="sp_modal-footer">';
				echo '<button id="sp_cancel" type="button" class="sp_cancel btn btn-default">'.__('Cancel', 'shareprints').'</button>';
				echo '<button id="sp_submit" type="button" class="btn btn-primary">'.__('Insert Gallery', 'shareprints').'</button>';
				echo '<button id="sp_update" type="button" class="btn btn-primary" style="display:none;">'.__('Update Gallery', 'shareprints').'</button>';
			echo '</div>';
  	
 		echo '</div>';
	echo '</div>';
echo '</div>';

// Secondary Modal
echo '<div id="shareprints_gallery_editor" class="fade sp_modal" role="dialog" aria-hidden="true">';// aria-labelledby="shareprints_gallery_editor_title" 
	echo '<div class="sp_modal-dialog">';
		echo '<div class="sp_modal-content">';

			// Modal Header
			echo '<div class="sp_modal-header">';
				echo '<h4 class="sp_modal-title">'.__('SharePrints - Add New Gallery', 'shareprints').'</h4>';
			echo '</div>';

			// Modal Body
			echo '<div id="gallery_meta_box" class="sp_modal-body"></div>';// Modal Body

			// Modal Footer
			echo '<div class="sp_modal-footer">';
				echo '<div class="sp_alert_holder"></div>';
				echo '<button id="sp_cancel_gallery" type="button" class="sp_cancel_gallery btn btn-default">'.__('Cancel', 'shareprints').'</button>';
				echo '<button id="sp_publish_gallery" type="button" class="btn btn-primary">'.__('Publish Gallery', 'shareprints').'</button>';
				echo '<button id="sp_update_gallery" type="button" class="btn btn-primary" style="display:none;">'.__('Update Gallery', 'shareprints').'</button>';
			echo '</div>';

		echo '</div>';
	echo '</div>';
echo '</div>';