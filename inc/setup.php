<?php
/**
 * Theme setup: supports, menus, image sizes and editor integration.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features.
 */
function crrg_setup() {
	load_theme_textdomain( 'crrg', CRRG_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'link-color' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'custom-background',
		array(
			'default-color'    => '',
			'wp-head-callback' => 'crrg_custom_background_cb',
		)
	);
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'crrg' ),
			'topbar'  => __( 'Top Bar Menu', 'crrg' ),
			'mobile'  => __( 'Mobile Menu (falls back to Primary)', 'crrg' ),
			'footer'  => __( 'Footer Menu', 'crrg' ),
			'landing' => __( 'Landing Page Menu', 'crrg' ),
		)
	);

	add_image_size( 'crrg-card', 720, 450, true );
	add_image_size( 'crrg-list', 480, 340, true );
	add_image_size( 'crrg-wide', 1280, 640, true );
	add_image_size( 'crrg-banner', 1920, 600, true );

	add_editor_style( 'assets/css/editor-style.css' );

	// Editor colours follow the Customizer palette.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Primary', 'crrg' ),
				'slug'  => 'crrg-primary',
				'color' => crrg_get_option( 'primary_color' ),
			),
			array(
				'name'  => __( 'Secondary', 'crrg' ),
				'slug'  => 'crrg-secondary',
				'color' => crrg_get_option( 'secondary_color' ),
			),
			array(
				'name'  => __( 'Accent', 'crrg' ),
				'slug'  => 'crrg-accent',
				'color' => crrg_get_option( 'accent_color' ),
			),
			array(
				'name'  => __( 'Heading', 'crrg' ),
				'slug'  => 'crrg-heading',
				'color' => crrg_get_option( 'heading_color' ),
			),
			array(
				'name'  => __( 'Text', 'crrg' ),
				'slug'  => 'crrg-text',
				'color' => crrg_get_option( 'text_color' ),
			),
			array(
				'name'  => __( 'Light background', 'crrg' ),
				'slug'  => 'crrg-light',
				'color' => crrg_get_option( 'body_bg_color' ),
			),
			array(
				'name'  => __( 'White', 'crrg' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
			array(
				'name'  => __( 'Black', 'crrg' ),
				'slug'  => 'black',
				'color' => '#000000',
			),
		)
	);

	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Small', 'crrg' ),
				'slug' => 'small',
				'size' => 14,
			),
			array(
				'name' => __( 'Normal', 'crrg' ),
				'slug' => 'normal',
				'size' => 17,
			),
			array(
				'name' => __( 'Large', 'crrg' ),
				'slug' => 'large',
				'size' => 24,
			),
			array(
				'name' => __( 'Extra large', 'crrg' ),
				'slug' => 'x-large',
				'size' => 36,
			),
			array(
				'name' => __( 'Huge', 'crrg' ),
				'slug' => 'huge',
				'size' => 52,
			),
		)
	);
}
add_action( 'after_setup_theme', 'crrg_setup' );

/**
 * Content width for embeds and images, based on layout.
 */
function crrg_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'crrg_content_width', 800 );
}
add_action( 'after_setup_theme', 'crrg_content_width', 0 );

/**
 * Only print the custom background when the user set one, so the
 * Customizer page background colour keeps working otherwise.
 */
function crrg_custom_background_cb() {
	if ( get_background_image() || get_background_color() ) {
		_custom_background_cb();
	}
}

/**
 * Excerpt length from the Customizer.
 *
 * @param int $length Default length.
 * @return int
 */
function crrg_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}

	return absint( crrg_get_option( 'excerpt_length' ) );
}
add_filter( 'excerpt_length', 'crrg_excerpt_length', 99 );

/**
 * Replace the [...] excerpt suffix with an ellipsis.
 *
 * @param string $more Default suffix.
 * @return string
 */
function crrg_excerpt_more( $more ) {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'crrg_excerpt_more' );
