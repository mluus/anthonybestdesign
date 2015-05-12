<?php
/*********************************
* These are the messages displayed when processing functions run
*********************************/
?>

<?php if(isset($_GET['image-added']) && $_GET['image-added'] == 'success') : ?>
	<div class="updated fade">
		<p><?php _e('New image added.', 'fsb'); ?></p>
	</div>
<?php elseif(isset($_GET['image-added']) && $_GET['image-added'] == 'failed') : ?>
	<div class="error fade">
		<p><?php _e('Adding image failed. Please try again. If the problem persists, contact <a href="mailto:pippin@pippinsplugins.com">support</a>.', 'fsb'); ?></p>
	</div>	
<?php endif; ?>	

<?php if(isset($_GET['image-edited']) && $_GET['image-edited'] == 'success') : ?>
	<div class="updated fade">
		<p><?php _e('Image updated.', 'fsb'); ?></p>
	</div>
<?php elseif (isset($_GET['image-edited']) && $_GET['image-edited'] == 'failed') : ?>
	<div class="fade error">
		<p><?php _e('Image update failed.', 'fsb'); ?></p>
	</div>
<?php endif; ?>	

<?php if(isset($_GET['image-deleted']) && $_GET['image-deleted'] == 'success') : ?>
	<div class="updated fade">
		<p><?php _e('Image has been deleted.', 'fsb'); ?></p>
	</div>
<?php elseif (isset($_GET['image-deleted']) && $_GET['image-deleted'] == 'failed') : ?>
	<div class="fade error">
		<p><?php _e('Image deletion failed.', 'fsb'); ?></p>
	</div>
<?php endif;