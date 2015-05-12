jQuery(function($){
	
	// Media Uploader
	$('.upload_image_button').live('click', function() {
		window.widgetFormfield = $('.upload_field',$(this).parent());
		tb_show('Choose Image', 'media-upload.php?TB_iframe=true');
		return false;
	});
	window.send_to_editor = function(html) {
		if (window.widgetFormfield) {
			imgurl = $('img',html).attr('src');
			window.widgetFormfield.val(imgurl);
			tb_remove();
		}
		else {
			window.original_send_to_editor(html);
		}
		window.widgetFormfield = '';
		window.imagefield = false;
		
		$('#fsb_preview_image').attr('src', imgurl); 
	}	
	// end media uploader

});