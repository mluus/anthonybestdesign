jQuery(function($){
	$("#fsb_table").tablesorter();
	
	// WP 3.5+ uploader
	var file_frame;
	$('body').on('click', '.upload_image_button', function(e) {

		e.preventDefault();

		var formfield = $('.upload_field', $(this).parent() );

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			frame: 'select',
			title: 'Choose Image',
			multiple: false,
			library: {
				type: 'image'
			},
			button: {
				text: 'Use as Image'
			}
		});

		file_frame.on( 'menu:render:default', function(view) {
	        // Store our views in an object.
	        var views = {};

	        // Unset default menu items
	        view.unset('library-separator');
	        view.unset('gallery');
	        view.unset('featured-image');
	        view.unset('embed');

	        // Initialize the views in our view object.
	        view.set(views);
	    });

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {

			var attachment = file_frame.state().get('selection').first().toJSON();
			formfield.val(attachment.url);
			$('#fsb_preview_image').attr('src', attachment.url); 
			$('#fsb_image_name').val(attachment.name); 

		});

		// Finally, open the modal
		file_frame.open();
	});	
	// end media uploader
	
	//check for image name and url
	$('.fsb_form').submit(function() {
		var src = $('#fsb_image_src').val();
		var name = $('#fsb_image_name').val();
		
		// check for url
		if(src == 'Enter image url . . .' || src == '') {
			alert('You must choose an image or enter the URL manually');
			return false;
		}
		// check for name
		if(name == '') {
			alert('You must enter an image name');
			return false;
		}
		
	});
	
	// show the optional IDs field if one of these contexts is selected on load
	var context = $('#fsb_image_context option:selected').val();
	if(context == 'posts' || context == 'pages' || context == 'category') {
		$('.restrict_ids').fadeIn();
	}
	$('#fsb_image_context').change(function() {
		var option = $('option:selected', this).val();
		if(option == 'pages' || option == 'category' || option == 'posts') {
			$('.restrict_ids').fadeIn();
		} else {
			$('.restrict_ids').fadeOut();
		}
	});
});