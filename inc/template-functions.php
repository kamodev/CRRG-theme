<?php
/**
 * Hooks that adjust WordPress output for the theme.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Body classes describing the active options.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function crrg_body_classes( $classes ) {
	$classes[] = 'layout-' . crrg_get_layout();
	$classes[] = crrg_has_sidebar() ? 'has-sidebar' : 'no-sidebar';
	$classes[] = 'site-' . crrg_get_option( 'site_layout' );
	$classes[] = 'header-' . crrg_get_option( 'header_layout' );
	$classes[] = 'cards-' . crrg_get_option( 'card_style' );
	$classes[] = 'width-' . ( 'fixed' === crrg_get_option( 'width_mode' ) ? 'fixed' : 'full' );
	$classes[] = 'mobile-menu-' . crrg_get_option( 'mobile_menu_style' );

	$classes[] = crrg_is_dark( 'header_bg_color' ) ? 'header-is-dark' : 'header-is-light';
	$classes[] = crrg_is_dark( crrg_get_option( 'hero_image' ) ? 'hero_overlay_color' : 'hero_bg_color' ) ? 'hero-is-dark' : 'hero-is-light';

	if ( crrg_get_option( 'sticky_header' ) ) {
		$classes[] = 'has-sticky-header';
	}
	if ( crrg_get_option( 'sticky_sidebar' ) ) {
		$classes[] = 'has-sticky-sidebar';
	}
	if ( crrg_get_option( 'topbar_enable' ) && ! crrg_is_landing() ) {
		$classes[] = 'has-topbar';
	}
	if ( is_front_page() && crrg_get_option( 'transparent_header_front' ) && crrg_get_option( 'hero_enable' ) ) {
		$classes[] = 'has-transparent-header';
	}
	if ( crrg_show_page_banner() ) {
		$classes[] = 'has-page-banner';
	}
	if ( crrg_is_landing() ) {
		$classes[] = 'is-landing-page';
	}
	if ( is_home() || is_archive() || is_search() ) {
		$classes[] = 'blog-style-' . crrg_get_option( 'blog_style' );
	}
	if ( is_singular() ) {
		$classes[] = 'is-singular';
	} else {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'crrg_body_classes' );

/**
 * Pingback header for single posts with pings open.
 */
function crrg_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'crrg_pingback_header' );

/**
 * Remove the "Category:" style prefixes from archive titles.
 *
 * @param string $title  Archive title.
 * @param string $original Title without the prefix.
 * @param string $prefix Prefix.
 * @return string
 */
function crrg_archive_title( $title, $original = '', $prefix = '' ) {
	if ( $prefix && $original ) {
		return '<span class="archive-title-prefix">' . esc_html( wp_strip_all_tags( $prefix ) ) . '</span> ' . $original;
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'crrg_archive_title', 10, 3 );

/**
 * Classes for a post in a list, based on the blog style.
 *
 * @param array $classes Post classes.
 * @return array
 */
function crrg_post_classes( $classes ) {
	if ( ! is_admin() && ( is_home() || is_archive() || is_search() ) && in_the_loop() ) {
		$classes[] = 'entry-card';
		$classes[] = 'entry-card--' . crrg_get_option( 'blog_style' );
	}

	return $classes;
}
add_filter( 'post_class', 'crrg_post_classes' );

/**
 * Number of columns for the post grid.
 *
 * @return int
 */
function crrg_blog_columns() {
	$columns = (int) crrg_get_option( 'blog_columns' );

	// Keep cards readable when sidebars take space.
	if ( 'both-sidebars' === crrg_get_layout() ) {
		$columns = 1;
	} elseif ( crrg_has_sidebar() ) {
		$columns = min( 2, $columns );
	}

	return max( 1, $columns );
}

/**
 * Wrap the comment form submit in a theme button class.
 *
 * @param array $defaults Comment form args.
 * @return array
 */
function crrg_comment_form_defaults( $defaults ) {
	$defaults['class_submit']       = 'submit crrg-button';
	$defaults['title_reply_before'] = '<h2 id="reply-title" class="comment-reply-title">';
	$defaults['title_reply_after']  = '</h2>';

	return $defaults;
}
add_filter( 'comment_form_defaults', 'crrg_comment_form_defaults' );
