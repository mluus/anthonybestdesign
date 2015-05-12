<?php

function fsb_pro_display_image() {
	global $fsb_db_name;
	global $wpdb;
	global $post;
	global $fsb_pro_options;
	$image_found = false;

	// first we need to check the kind of page we're on in order to pull the correct image
	if ( is_category() ) {
		$context = 'category';
	} elseif ( is_archive() ) {
		$context = 'archives';
	} elseif ( is_front_page() ) {
		$context = 'front';
	} elseif ( is_home() ) {
		$context = 'blog';
	} elseif ( is_page() ) {
		$context = 'pages';
	} elseif ( is_search() ) {
		$context = 'search';
	} elseif ( is_single() ) {
		$context = 'posts';
	} elseif ( is_404() ) {
		$context = '404';
	} else {
		$context = 'global';
	}

	$context = apply_filters( 'fsb_context_displayed', $context );

	// retrieve all the images for this context
	$images = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $fsb_db_name . " WHERE context='%s';", $context ) );
	if ( $images ) {

		// set up an array of the contexts that accept ID restrictions
		$id_restricted_contexts = array( 'category', 'pages', 'posts' );
		$found_images = array();
		$no_id_images = array();
		foreach ( $images as $image ) {
			// now find an image that matches the ID of the current page
			// restriction IDs stored in the imaage DB are placed in an array, then the current page ID is checked against the array
			if ( in_array( $context, $id_restricted_contexts ) && ! empty( $image->page_ids ) ) {

				// setup a blank array for the page IDs
				$pages_array = array();

				// separate individual page IDS/slugs into an array and remove spaces (otherwise slug identification will fail)
				$pages_array = explode( ',', str_replace( ' ', '', $image->page_ids ) );

				if ( $pages_array[0] != '' ) {

					// first images for this context that are limited to specific IDs must be checked
					if ( is_numeric( $pages_array[0] ) ) {
						// using IDs for current page identification

						// check if the context refers to a category or post/page ID
						if ( $context == 'category' ) {
							$current_page = get_query_var( 'cat' );
						} else {
							$current_page = $post->ID;
						}

					} else {
						// using slugs for current page identification

						// check if the context refers to a category or post/page ID
						if ( $context == 'category' ) {
							$current_page = fsb_get_cat_slug( get_query_var( 'cat' ) );
						} else {
							$current_page = $post->post_name;
						}
					}

					if ( in_array( $current_page, $pages_array ) ) {

						// an image for the current page ID/slug has been found
						$image_found = true;
						$found_images[] = $image->url;
					}

				} // end checks for whether this image matches the current post/page/category id

				// this image is not restricted to a certain context, so let's store it in an array for use later (maybe)
			} else {
				// store each image without an ID restriction in an array
				$no_id_images[] = $image->url;
			}
		} // end foreach

		// if there were no images found for this context that have an ID restriction, use the first image from the $no_id_images array
		// if there are no images in this array, $image_found is false and we try for a global image
		if ( count( $no_id_images ) && $image_found == false ) {
			$image_found = true;
		}
	} // end $images

	// if no images for current context are found, check for a global image
	if ( $image_found == false ) {
		$images = $wpdb->get_results( "SELECT url FROM " . $fsb_db_name . " WHERE context='global';" );
		if ( $images ) {
			$image_found = true;
			$no_id_images = $images;
		}
	}

	// only output the image if one is found
	if ( $image_found == true ) {

		$src = array();

		if( empty( $found_images ) ) {
			$found_images = $no_id_images;
		}

		foreach ( $found_images as $image ) {

			if( is_object( $image ) )
				$image_src = $image->url;
			else
				$image_src = $image;

			$src[] = apply_filters( 'fsb_image_source', $image_src );

		}
		wp_localize_script( 'fsb-scripts', 'fsb_src', array( 'images' => $src ) );
	}
}
add_action( 'wp_enqueue_scripts', 'fsb_pro_display_image' );
