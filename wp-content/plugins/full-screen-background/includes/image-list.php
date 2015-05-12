<?php
// lists all the images
?>

<div class="wrap">
	<h2><?php _e('Full Screen Background Image', 'fsb'); ?></h2>

	<?php include('admin-messages.php'); ?>

	<p>
		<a class="button-primary" id="add_new_image_button_top" href="themes.php?page=full-screen-background&action=add_new_image"><?php _e( 'Add New Image', 'fsb' ); ?></a>
	</p>

	<table class="wp-list-table widefat fixed posts" id="fsb_table">
		<thead>
			<tr>
				<th style="width: 40px;"><?php _e('ID', 'fsb'); ?></th>
				<th style="width: 100px;"><?php _e('Thumb', 'fsb'); ?></th>
				<th><?php _e('Name', 'fsb'); ?></th>
				<th style="width: 100px;"><?php _e('Context', 'fsb'); ?></th>
				<th><?php _e('Post/Page/Category IDs', 'fsb'); ?></th>
				<th><?php _e('Edit', 'fsb'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php _e('ID', 'fsb'); ?></th>
				<th><?php _e('Thumb', 'fsb'); ?></th>
				<th><?php _e('Name', 'fsb'); ?></th>
				<th><?php _e('Context', 'fsb'); ?></th>
				<th><?php _e('Post/Page/Category IDs', 'fsb'); ?></th>
				<th><?php _e('Edit', 'fsb'); ?></th>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$images = $wpdb->get_results("SELECT * FROM " . $fsb_db_name . " ORDER BY id;");
		if($images) :
			foreach( $images as $key => $image) { ?>
				<tr>
					<td><?php echo $image->id; ?></td>
					<td>
						<?php
						$image_id = fsb_get_image_id($image->url);
						$image_thumb = wp_get_attachment_image_src($image_id, 'fsb-thumb');
						if(!$image_thumb) {
							$image_thumb[0] = $image->url;
						}
						?>
						<img class="fsb_thumb" src="<?php echo $image_thumb[0]; ?>"/>
					</td>
					<td><?php echo $image->name; ?></td>
					<td><?php echo $image->context; ?></td>
					<td>
						<?php
							$no_id_restriction = array(__('Global', 'fsb'), __('Archives', 'fsb'), __('Front Page', 'fsb'), __('Blog', 'fsb'), __('Search', 'fsb'), __('404', 'fsb'));
							if(in_array($image->context, $no_id_restriction)) {
								echo '<em>This context cannot be limited to certain IDs</em>';
							} else {
								echo $image->page_ids;
							}
						?>
					</td>
					<td>
						<a class="button-secondary" href="themes.php?page=full-screen-background&action=edit_image&image_id=<?php echo $image->id; ?>"><?php _e( 'Edit Image', 'fsb' ); ?></a>
						<a class="button-secondary" href="themes.php?page=full-screen-background&action=delete_image&image_id=<?php echo $image->id; ?>"><?php _e( 'Delete', 'fsb' ); ?></a>
					</td>
				</tr>
			<?php }
		else : ?>
			<tr>
				<td colspan=6><?php _e('You have not created any images yet.', 'fsb'); ?>
			</tr>
		<?php endif;?>
		</tbody>
	</table>

	<p class="submit">
		<a class="button-primary" id="add_new_image_button" href="themes.php?page=full-screen-background&action=add_new_image"><?php _e( 'Add New Image', 'fsb' ); ?></a>
	</p><br/>

	<h3><?php _e('Settings', 'fsb'); ?></h3>
	<?php if ( ! isset( $_REQUEST['settings-updated'] ) ) { $_REQUEST['settings-updated'] = false; } ?>
	<?php if ( true == $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'fsb' ); ?></strong></p></div>
	<?php endif; ?>
	<form method="post" action="options.php">

		<?php
			global $fsb_pro_options;
			settings_fields( 'fsb_pro_register_settings' );
			do_settings_sections( 'fsb_pro_register_settings' );
		?>

		<p><?php _e('The fadein effect is applied to the background images when the page is loaded to give a smoother load.', 'fsb'); ?></p>
		<p>
			<input id="fsb_pro_settings[fadein_wait]" name="fsb_pro_settings[fadein_wait]" type="text" style="width:50px;" value="<?php echo $fsb_pro_options['fadein_wait']; ?>"/>
			<label class="description" for="fsb_pro_settings[fadein_wait]"><?php _e( 'Enter the wait time for image fade in. This is the amount of time in milliseconds before images begin to fade in.', 'fsb' ); ?></label>
		</p>
		<p>
			<input id="fsb_pro_settings[fadein_speed]" name="fsb_pro_settings[fadein_speed]" type="text" style="width:50px;" value="<?php echo $fsb_pro_options['fadein_speed']; ?>"/>
			<label class="description" for="fsb_pro_settings[fadein_speed]"><?php _e( 'Enter the image fade duration. This is how long an image takes to fully fade in. This time is in milliseconds.', 'fsb' ); ?></label>
		</p>
		<p>
			<input id="fsb_pro_settings[license]" name="fsb_pro_settings[license]" type="text"value="<?php echo isset( $fsb_pro_options['license'] ) ? $fsb_pro_options['license'] : ''; ?>"/>
			<label class="description" for="fsb_pro_settings[license]"><?php _e( 'Enter your license key.', 'fsb' ); ?></label>
			<?php if( 'valid' == get_option( 'fsb_license_key_active' ) ) { ?>
				<?php wp_nonce_field( 'fsb_license_nonce', 'fsb_license_nonce' ); ?>
				<input type="submit" class="button-secondary" name="fsb_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
			<?php } ?>
		</p>

		<!-- save the options -->
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'fsb' ); ?>" />
		</p>

	</form>

</div><!--end wrap-->