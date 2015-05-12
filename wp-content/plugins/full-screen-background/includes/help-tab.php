<?php


if ( get_bloginfo('version') < 3.3 ) {
	// use old help tab format for WP version less than 3.3
	include('help-tab-setup-old.php');
} else {
	// use the new, better format
	include('help-tab-setup.php');
}


function fsb_render_help_tab_content($id) {
	switch($id) :
	
		case 'purpose' :
			ob_start(); ?>
			<p>This plugin is designed to provide a very easy to use interface for adding full screen background images to any page of your WordPress site. As designed, the plugin should work fine with the majority of WordPress themes that follow good development standards, however I cannot guarantee complete compatibility with all themes due to the wide range of quality available in the theme world.</p>
				
			<p>A "full screen background image" is one that fills the complete background area of the web browser, regardless of size. This plugin will dynamically scale your images to fit within the viewable area. It will also resize images on the fly as browser windows are resized. In order to best ensure a high quality image is always displayed to your users, you should always upload reasonably large image sizes. Images smaller than 1024 are not recommended.</p>
			<?php
			break;
		case 'adding_images' :
			ob_start(); ?>
			<p>You can upload an unlimited number of images in this plugin. To add a new one, simply click the "Add New Image" button at either the top or the bottom of the main images list page. This will take you to the "Add New Image" page. From here, click the "Choose Image" button. This will cause the media manager window to open. Once you've done this, either choose a new image from your computer, or select one from the media library.</p>
			
			<p>Once you have chosen your image, select the image size you want (99% of the time you will want to choose the full size image size), then click "Insert Into Post". This will cause the popup window to close and the URL of the image will appear in the image URL field.</p>
			
			<p>Next, enter a name for your image. This name is purely for organization's sake and will never be displayed on the website.</p>
			
			<p>After you have entered the image name, choose a context on which to show the image. This select option allows you to define the pages on which the image will be displayed on your website. Below is an explanation for each context:</p>
			
			<ul>
				<li><strong>Global</strong> - This is the "site wide" context, meaning that any image given this context will be displayed on every single page, category, archive, post, etc. If more than one image have the global context, the image that was created first will be displayed. This is the "fallback" context, meaning that images set to other contexts will be displayed before any image with a Global context. If you want to set a single image to be displayed on every page of your site, use this context.</li>
				<li><strong>Category</strong> - Any image given this context will be displayed on category pages. This option also takes an additional input field that allows you to restrict the image display to particular categories. If no specific category is chosen, the image will be displayed on all category pages (except those that have an image attached to them by the option field). Also if there is more than one image without a category specified, the first image added will be displayed on category pages. To restrict an image to a specific category (or categories), enter the ID number or the slug of the category. If you wish to set the image to more than one category, separate image IDs or slugs by a comma.</li>
				<li><strong>Archives</strong> - Setting an image to this context will make it display on all archive pages, this means both category and date based archives. Note, however, that there is a hierarchy. If an image is set to the Category context, it will override any image set to the Archives context. The Archive context cannot accept restriction IDs or slugs.</li>
				<li><strong>Pages</strong> - This context allows you to set an image to display on WordPress Pages. This option also takes an additional input field that allows you to restrict the image display to particular pages. If no specific page is entered, the image will be displayed on all pages (except those that have an image attached to them by the optional field). Also if there is more than one image without a page specified, the first image added will be displayed on pages. To restrict an image to a specific page (or pages plural), enter the ID number or the slug of the page. If you wish to set the image to more than one page, separate image IDs or slugs by a comma.</li>
				<li><strong>Posts</strong> - This context allows you to set an image to display on individual Post pages. This option also takes an additional input field that allows you to restrict the image display to particular posts. If no specific post is entered, the image will be displayed on all posts (except those that have an image attached to them by the optional field). Also if there is more than one image without a post specified, the first image added will be displayed on posts. To restrict an image to a specific post (or posts plural), enter the ID number or the slug of the post. If you wish to set the image to more than one post, separate image IDs or slugs by a comma.</li>
				<li><strong>Blog</strong> - The Blog context lets you specify the image that will be displayed on the Blog index page. This is the page that lists all of your WordPress posts. This context can have only one image. If more that one image is set to the Blog context, the one added first will be displayed.</li>
				<li><strong>Front Page</strong> - This is the context for displaying an image on the front or home page of your WordPress site. By setting an image to this context, the image will be displayed on the page that is set under the "Reading" settings page in the main WordPress settings. This context can only have one image and only the first image added will be displayed if more than one is set.</li>
				<li><strong>Search</strong> - This is the context for displaying an image on the Search results page. Any image you give this context will be displayed after performing a search on your WordPress site. This context can only take one image and only the first image added will be displayed if there are more than one images with the Search context.</li>
				<li><strong>404</strong> - This is the context for displaying an image on "404 not found" error page. This context can only take one image and only the first image added will be displayed if there are more than one images with the Search context.</li>
			</ul>

			<p>After you have chosen your context, simply click "Save Image". You will now be redirected back to the plugin home screen and your image will be at the bottom of the list.</p>
			
			<p><strong>Note</strong>: You can also add images directly from the Post / Page editor. When adding an image from the editor, the uploaded image will automatically be given a restriction ID of the post/page that is being edited. The newly added image will also be added to the master image list and can be edited from either location.</p>
			<?php
			break;
		case 'editing_images' :
			ob_start(); ?>
			<p>Images can be edited at anytime. To change an image, click the "Edit Image" button in the right "edit" column. Once you've clicked this button, you will be taken to a page nearly identical to the add new image page, except all of your image information will already be entered. Change what you want in exactly the same manner as adding a new image (read the steps above if necessary), and click "Save Image" when finished.</p>
			<?php
			break;
		case 'removing_images' :
			ob_start(); ?>
			<p>Images can be deleted at any time. To remove an image completely, simply click "Delete" in the right "Edit" column on the image that you wish to delete. You will be asked to confirm your choice. Once you do, the image will be removed from the database. The image will, however, remain in the media library, so you can easily add it back at any time.</p>
			<?php
			break;
					
		default;
			break;
			
	endswitch;
	
	return ob_get_clean();
}