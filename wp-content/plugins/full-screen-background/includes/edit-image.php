<?php
// add new image
$load_scripts = true;
?>
<div class="wrap">
	<h2><?php _e('Edit Image', 'fsb'); ?> - <a href="<?php echo site_url(); ?>/wp-admin/themes.php?page=full-screen-background" class="button-secondary"><?php _e('Cancel - Go Back', 'fsb'); ?></a></h2>
	<form method="post" action="" class="fsb_form">
		<?php $image = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $fsb_db_name . " WHERE id='%d';", $_GET['image_id'] ) ); ?>
		<p>
			<label class="description" for="fsb_image_src"><?php _e( 'Choose an image. <strong>Note</strong>: ensure you select "Full Size" when choosing your image.', 'fsb' ); ?></label>
			<input id="fsb_image_src" name="fsb_image_src" type="text" class="upload_field widefat" value="<?php echo $image->url; ?>"/>
			<input class="upload_image_button button-secondary" type="button" value="<?php _e('Choose Image', 'fsb'); ?>"/><br/>
		</p>
		<p>
			<label class="description" for="fsb_image_name"><?php _e('Enter image name', 'fsb'); ?></label>
			<input class="widefat" name="fsb_image_name" value="<?php echo $image->name; ?>" type="text"/>
		</p>
		<p>
			<label class="description" for="fsb_image_context"><?php _e('Choose the context on which to display this image', 'fsb'); ?></label>
			<select id="fsb_image_context" name="fsb_image_context">
				<?php 
					foreach( fsb_get_contexts() as $key => $context ) {
						echo '<option value="' . esc_attr( $key ) . '"' . selected( $key, $image->context, false ) . '>' . $context . '</option>';
					}
				?>
			</select>
		</p>
		<p class="restrict_ids">
			<label class="description" for="fsb_image_page_ids"><?php _e('Post/Page/Category IDs or slugs to restrict images to. <strong>Note: do not combine slugs and IDs</strong>. Use one or the other.', 'fsb'); ?></label>
			<input class="widefat" name="fsb_image_page_ids" value="<?php echo $image->page_ids; ?>" type="text"/> (<?php _e('Optional', 'fsb'); ?>)
		</p><br/>
		<p>
			<hr/><br/>
			<input type="hidden" name="edit_image" value="<?php echo $image->id; ?>"/>
			<input type="hidden" name="fsb_nonce" value="<?php echo wp_create_nonce('fsb-nonce'); ?>"/>
			<input type="submit" name="fsb_save" class="button-primary" value="<?php _e( 'Save Image', 'fsb' ); ?>" />
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