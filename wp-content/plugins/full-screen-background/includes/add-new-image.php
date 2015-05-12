<?php
// add new image
?>
<div class="wrap">
	<h2><?php _e('Add New Image', 'fsb'); ?> - <a href="<?php echo site_url(); ?>/wp-admin/themes.php?page=full-screen-background" class="button-secondary"><?php _e('Cancel - Go Back', 'fsb'); ?></a></h2>

	<form method="post" action="" class="fsb_form">
		<p>
			<label class="description" for="fsb_image_src"><?php _e( 'Choose an image. <strong>Note</strong>: ensure you select "Full Size" when choosing your image.', 'fsb' ); ?></label>
			<input id="fsb_image_src" name="fsb_image_src" type="text" class="upload_field" value="<?php _e('Choose image', 'fsb'); ?>" onfocus="if (this.value == '<?php _e('Choose image . . .', 'fsb'); ?>') { this.value=''; }" onblur="if (this.value == '') { this.value='<?php _e('Choose image . . .', 'fsb'); ?>'; }"/>
			<input class="upload_image_button button-secondary" type="button" value="<?php _e('Choose Image', 'fsb'); ?>"/>
		</p>
		<p>
			<label class="description" for="fsb_image_name"><?php _e('Enter image name', 'fsb'); ?></label>
			<input class="widefat" id="fsb_image_name" name="fsb_image_name" type="text" />
		</p>
		<p>
			<label class="description" for="fsb_image_context"><?php _e('Choose the context on which to display this image', 'fsb'); ?></label>
			<select id="fsb_image_context" name="fsb_image_context">
				<?php 
					foreach( fsb_get_contexts() as $key => $context ) {
						echo '<option value="' . esc_attr( $key ) . '">' . $context . '</option>';
					}
				?>
			</select>
		</p>
		<p class="restrict_ids">
			<label class="description" for="fsb_image_page_ids"><?php _e('Enter Post/Page/Category IDs or slugs to restrict images to. <strong>Note: do not combine slugs and IDs</strong>. Use one or the other', 'fsb'); ?></label>
			<input class="widefat" name="fsb_image_page_ids" value="" type="text"/> (<?php _e('Optional', 'fsb'); ?>)
		</p><br/>
		<p>
			<hr/><br/>
			<input type="hidden" name="add_new_image" value="1"/>
			<input type="hidden" name="fsb_nonce" value="<?php echo wp_create_nonce('fsb-nonce'); ?>"/>
			<input type="submit" class="button-primary" value="<?php _e( 'Save Image', 'fsb' ); ?>" />
		</p>
	</form>
	<div id="fsb_edit_preview">
		<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/default-preview-image.png'; ?>" id="fsb_preview_image" />
	</div>
</div>