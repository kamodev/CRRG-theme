<?php
/**
 * Front-end and editor assets.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front-end styles and scripts.
 */
function crrg_enqueue_assets() {
	$fonts_url = crrg_google_fonts_url();
	if ( $fonts_url ) {
		wp_enqueue_style( 'crrg-fonts', $fonts_url, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- the Google Fonts API does not need a version.
	}

	wp_enqueue_style( 'crrg-theme', CRRG_URI . '/assets/css/theme.css', array(), CRRG_VERSION );

	wp_enqueue_script(
		'crrg-navigation',
		CRRG_URI . '/assets/js/navigation.js',
		array(),
		CRRG_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_enqueue_script(
		'crrg-theme',
		CRRG_URI . '/assets/js/theme.js',
		array(),
		CRRG_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'crrg_enqueue_assets' );

/**
 * Block editor: load the fonts and the Customizer variables inside the
 * editor canvas so content looks the same as on the front end.
 *
 * The enqueue_block_assets hook also runs on the front end, where the
 * regular stylesheet already covers this.
 */
function crrg_enqueue_editor_canvas_assets() {
	if ( ! is_admin() ) {
		return;
	}

	$fonts_url = crrg_google_fonts_url();
	if ( $fonts_url ) {
		wp_enqueue_style( 'crrg-editor-fonts', $fonts_url, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}

	wp_register_style( 'crrg-editor-variables', false, array(), CRRG_VERSION );
	wp_enqueue_style( 'crrg-editor-variables' );
	wp_add_inline_style( 'crrg-editor-variables', crrg_css_variables() );
}
add_action( 'enqueue_block_assets', 'crrg_enqueue_editor_canvas_assets' );
