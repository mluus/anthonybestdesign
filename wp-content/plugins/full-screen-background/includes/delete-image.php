<?php
// delete an image
?>
<div class="wrap">
	<h2><?php _e('Delete Image', 'fsb'); ?> - <a href="<?php echo site_url(); ?>/wp-admin/themes.php?page=full-screen-background" class="button-secondary"><?php _e('Cancel - Go Back', 'fsb'); ?></a></h2>
	<form method="post" action="" class="fsb_form">
		<?php $image = $wpdb->get_row("SELECT * FROM " . $fsb_db_name . " WHERE id='" . $_GET['image_id'] . "';"); ?>
		<p><?php _e('Image deletion is permanent and cannot be undone.', 'fsb'); ?></p>
		<p><?php _e('Are you sure you wish to delete yes this image?', 'fsb'); ?></p>
		<p>
			<input type="hidden" name="fsb_nonce" value="<?php echo wp_create_nonce('fsb-nonce'); ?>"/>
			<input type="hidden" name="delete_image" value="<?php echo $image->id; ?>"/>
			<input type="submit" class="button-primary" value="<?php _e( 'Yes, Delete Image', 'fsb' ); ?>" />
		</p>
	</form>
	<div id="fsb_edit_preview">
		<?php
		$image_id = fsb_get_image_id($image->url);
		$image_resized = wp_get_attachment_image_src($image_id, 'fsb-preview');
		if(!$image_resized) {
			$image_resized[0] = $image->url;
		}
		?>
		<img src="<?php echo $image_resized[0]; ?>" id="fsb_preview_image"/>	
	</div>
</div>