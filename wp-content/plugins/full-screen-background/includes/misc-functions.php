<?php

function fsb_get_contexts() {

	$contexts = array(
		'global'   => __('Global', 'fsb'),
		'category' => __('Category', 'fsb'),
		'archives' => __('Archives', 'fsb'),
		'pages'    => __('Pages', 'fsb'),
		'posts'    => __('Posts', 'fsb'),
		'blog'     => __('Blog', 'fsb'),
		'front'    => __('Front Page', 'fsb'),
		'search'   => __('Search', 'fsb'),
		'404'      => __('404', 'fsb')
	);

	return apply_filters( 'fsb_contexts', $contexts );
}

function fsb_get_extension($str) {
   $parts = explode('.', $str);
   return end($parts);
}

function fsb_get_cat_slug($cat_id) {
	$cat_id = (int)$cat_id;
	$category = &get_category($cat_id);
	return $category->slug;
}

// retrieves the attachment ID from the file URL
function fsb_get_image_id($image_url) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$attachment = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $image_url ) );
	if($attachment)
		return $attachment[0];	
}

function fsb_check_ssl( $image_src ) {

	if( is_ssl() ) {
		$image_src = str_replace( 'http://', 'https://', $image_src );
	}
	return $image_src;

}
add_filter( 'fsb_image_source', 'fsb_check_ssl' );