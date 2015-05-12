<?php

function fsb_add_meta_box() {
	$posttypes = get_post_types(array('show_ui' => true), 'objects');
	foreach($posttypes as $posttype) {
		// this is to make sure the meta box is not added to the Sugar Modal Windows CPT
		if( $posttype->name != 'modals' ) {
			add_meta_box('fsb-meta-box', __('Full Screen Background Image',' fsb'), 'fsb_show_meta_box', $posttype->name, 'normal', 'high');
		}
	}
}
add_action('add_meta_boxes', 'fsb_add_meta_box');

function fsb_show_meta_box($post) {
	
	global $wpdb;
	global $fsb_db_name;
	
	if (get_post_type($post) == 'page') {
		$context = 'Pages';
	} else {
		$context = 'Posts';
	}
	
	echo '<input type="hidden" name="fsb_meta_box_nonce" value="'. wp_create_nonce('fsb_meta_box'). '" />';	
	
	$image = $wpdb->get_row("SELECT * FROM " . $fsb_db_name . " WHERE parent_post='" . $post->ID . "' AND context='" . $context . "';");
	if( ! $image ) {
		$image = new StdClass;
		$image->url = '';
		$image->id = '';
		$image->name = '';
		$image->needs_updated = '';
		$image->page_ids = '';
	}
	?>

	<table class="form-table">
		<tr valign="top">
            <th scope="row"><label for="fsb_image_src"><?php _e('Choose Image', 'fsb'); ?></label></th>
            <td>
				<input id="fsb_image_src" name="_fsb_image_src" type="text" style="width: 290px;" class="upload_field" value="<?php echo isset($image->url) ? $image->url : ''; ?>"/>
				<input class="upload_image_button button-secondary" type="button" value="<?php _e('Choose Image', 'fsb'); ?>"/>
				
			</td>
        </tr>
		<tr valign="top">
            <th scope="row"><label for="fsb_image_name"><?php _e('Image Name', 'fsb'); ?></label></th>
            <td>
				<input id="fsb_image_name" name="_fsb_image_name" type="text" class="widefat" style="width: 400px;" value="<?php echo isset($image->name) ? $image->name : ''; ?>"/>				
				<input id="fsb_image_update" name="_fsb_image_update" type="hidden" value="<?php echo $image->needs_updated; ?>"/>				
				<input id="fsb_image_id" name="_fsb_image_id" type="hidden" value="<?php echo $image->id; ?>"/>				
				<input id="fsb_image_page_ids" name="_fsb_image_page_ids" type="hidden" value="<?php echo $image->page_ids; ?>"/>				
			</td>
        </tr>
		<tr valign="top">
            <th scope="row"><label for="fsb_image_name"><?php _e('Image Preview', 'fsb'); ?></label></th>
            <td>
				<div id="fsb_edit_preview">
					<?php if(! empty( $image->url ) ) { ?>	
						<?php
						$image_id = fsb_get_image_id($image->url);
						$image_resized = wp_get_attachment_image_src($image_id, 'fsb-preview');
						if(!$image_resized) {
							$image_resized[0] = $image->url;
						}
						?>
						<img src="<?php echo esc_url( $image_resized[0] ); ?>" id="fsb_preview_image" style="padding: 3px; border: 1px solid #f0f0f0; max-width: 400px; overflow: hidden;"/>
					<?php } else { ?>
						<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/default-preview-image.png'; ?>" style="padding: 3px; border: 1px solid #f0f0f0; max-width: 400px; overflow: hidden;" id="fsb_preview_image"/>
					<?php } ?>
				</div>
			</td>
        </tr>
    </table>
<?php

}

function fsb_save_meta_box($post_id) {
	global $wpdb;
	global $fsb_db_name;
	
	if (!isset($_POST['fsb_meta_box_nonce']) || !wp_verify_nonce($_POST['fsb_meta_box_nonce'], 'fsb_meta_box')) {
		return $post_id;
	}
	if ('post' == $_POST['post_type']) {
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_page', $post_id)) {
		return $post_id;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	if(wp_is_post_revision($post_id)) {
		return $post_id;
	}
	
	if ('page' == $_POST['post_type']) {
		$context = 'Pages';
	} else {
		$context = 'Posts';
	}
	
	// we've gotten past the security checks, so now let's save the data
	if($_POST['_fsb_image_update'] == 1 && isset($_POST['_fsb_image_src'])) {
		
		$page_ids = $_POST['_fsb_image_page_ids'];
		$page_ids .= ',' . $post_id;
		
		
		$edit_image = $wpdb->update( $fsb_db_name, 
			array(
				'name' => $_POST['_fsb_image_name'], 
				'url' => $_POST['_fsb_image_src'], 
				'context' => $context, 
				'needs_updated' => 1,
				'parent_post' => $post_id
			), 
			array(
				'id' => $_POST['_fsb_image_id'] 
			) 
		);
	} elseif(isset($_POST['_fsb_image_src']) && $_POST['_fsb_image_src'] != '' && $_POST['_fsb_image_update'] != 1) { // adding a new image
		$new_image = $wpdb->insert( $fsb_db_name, 
			array(
				'name' => $_POST['_fsb_image_name'], 
				'url' => $_POST['_fsb_image_src'], 
				'context' => $context, 
				'page_ids' => $post_id,
				'needs_updated' => 1,
				'parent_post' => $post_id,
			)
		);
	}
}
add_action('save_post', 'fsb_save_meta_box');