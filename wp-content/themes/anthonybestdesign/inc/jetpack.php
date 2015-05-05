<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package anthonybestdesign
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function anthonybestdesign_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'anthonybestdesign_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function anthonybestdesign_jetpack_setup
add_action( 'after_setup_theme', 'anthonybestdesign_jetpack_setup' );

function anthonybestdesign_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function anthonybestdesign_infinite_scroll_render