<?php

// this file handles all image processing

function fsb_process_images() {

	global $wpdb;
  	global $fsb_db_name;

	$fsb_post = (!empty($_POST)) ? true : false;
	if($fsb_post) // if data is being sent
	{

		if( ! current_user_can( 'manage_options' ) )
			return;

		// process the "add new image" function
		if(isset($_POST['add_new_image']) && $_POST['add_new_image'] == 1 && wp_verify_nonce($_POST['fsb_nonce'], 'fsb-nonce')) {
			$new_image = $wpdb->insert( $fsb_db_name,
				array(
					'name' => $_POST['fsb_image_name'],
					'url' => $_POST['fsb_image_src'],
					'context' => $_POST['fsb_image_context'],
					'page_ids' => $_POST['fsb_image_page_ids'],
					'needs_updated' => 0
				)
			);
			if($new_image) {
				$url = get_bloginfo('wpurl') . '/wp-admin/themes.php?page=full-screen-background&image-added=success';
			} else {
				$url = get_bloginfo('wpurl') . '/wp-admin/themes.php?page=full-screen-background&image-added=failed';
			}
			wp_redirect( $url ); exit;
		}

		// process updates to an image
		if(isset($_POST['edit_image']) && wp_verify_nonce($_POST['fsb_nonce'], 'fsb-nonce')) {
			$edit_image = $wpdb->update( $fsb_db_name,
				array(
					'name' => $_POST['fsb_image_name'],
					'url' => $_POST['fsb_image_src'],
					'context' => $_POST['fsb_image_context'],
					'page_ids' => $_POST['fsb_image_page_ids'],
					'needs_updated' => 1
				),
				array(
					'id' => $_POST['edit_image']
				)
			);
			if($edit_image) {
				$url = get_bloginfo('wpurl') . '/wp-admin/themes.php?page=full-screen-background&image-edited=success';
			} else {
				$url = get_bloginfo('wpurl') . '/wp-admin/themes.php?page=full-screen-background&image-edited=failed';
			}
			wp_redirect( $url ); exit;
		}

		// process image deletions
		if(isset($_POST['delete_image']) && wp_verify_nonce($_POST['fsb_nonce'], 'fsb-nonce')) {
			$delete_image = $wpdb->query( $wpdb->prepare( "DELETE FROM " . $fsb_db_name . " WHERE id = '%d';", absint( $_POST['delete_image'] ) ) );
			if($delete_image) {
				$url = get_bloginfo('wpurl') . '/wp-admin/themes.php?page=full-screen-background&image-deleted=success';
			} else {
				$url = get_bloginfo('wpurl') . '/wp-admin/themes.php?page=full-screen-background&image-deleted=failed';
			}
			wp_redirect( $url ); exit;
		}
	}
}
add_action('admin_init', 'fsb_process_images');