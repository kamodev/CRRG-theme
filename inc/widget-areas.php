<?php
/**
 * Widget areas and custom widgets.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register widget areas.
 */
function crrg_widgets_init() {
	$card = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);

	$areas = array(
		'sidebar-1'         => array(
			'name'        => __( 'Primary Sidebar', 'crrg' ),
			'description' => __( 'Main sidebar for the blog, posts and archives. In left-sidebar layouts it moves to the left.', 'crrg' ),
		),
		'sidebar-page'      => array(
			'name'        => __( 'Page Sidebar', 'crrg' ),
			'description' => __( 'Sidebar for pages. When empty, pages use the Primary Sidebar.', 'crrg' ),
		),
		'sidebar-secondary' => array(
			'name'        => __( 'Secondary Sidebar', 'crrg' ),
			'description' => __( 'Left-hand sidebar used by the "Sidebars on both sides" layout.', 'crrg' ),
		),
		'sidebar-custom-1'  => array(
			'name'        => __( 'Custom Sidebar 1', 'crrg' ),
			'description' => __( 'Choose this sidebar for individual posts or pages in the "CRRG Layout" box.', 'crrg' ),
		),
		'sidebar-custom-2'  => array(
			'name'        => __( 'Custom Sidebar 2', 'crrg' ),
			'description' => __( 'Choose this sidebar for individual posts or pages in the "CRRG Layout" box.', 'crrg' ),
		),
		'header-widget'     => array(
			'name'          => __( 'Header', 'crrg' ),
			'description'   => __( 'Shown beside the logo with the "Logo left with header widget area" header layout. Good for net times or a short notice.', 'crrg' ),
			'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="header-widget__title">',
			'after_title'   => '</span>',
		),
		'front-page-top'    => array(
			'name'        => __( 'Front Page Top', 'crrg' ),
			'description' => __( 'Full-width area on the front page. Position it under Front Page Sections &rarr; Section Order.', 'crrg' ),
		),
		'front-page-bottom' => array(
			'name'        => __( 'Front Page Bottom', 'crrg' ),
			'description' => __( 'Full-width area on the front page. Position it under Front Page Sections &rarr; Section Order.', 'crrg' ),
		),
		'before-content'    => array(
			'name'        => __( 'Before Content', 'crrg' ),
			'description' => __( 'Shown above the main content on every page except the front page.', 'crrg' ),
		),
		'after-content'     => array(
			'name'        => __( 'After Content', 'crrg' ),
			'description' => __( 'Shown below the main content on every page except the front page.', 'crrg' ),
		),
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		$areas[ 'footer-' . $i ] = array(
			/* translators: %d: footer column number. */
			'name'        => sprintf( __( 'Footer %d', 'crrg' ), $i ),
			/* translators: %d: footer column number. */
			'description' => sprintf( __( 'Footer column %d. Set the number of columns under CRRG Theme Options &rarr; Footer.', 'crrg' ), $i ),
		);
	}

	foreach ( $areas as $id => $args ) {
		register_sidebar( array_merge( $card, array( 'id' => $id ), $args ) );
	}
}
add_action( 'widgets_init', 'crrg_widgets_init' );

/**
 * Register custom widgets.
 */
function crrg_register_widgets() {
	register_widget( 'CRRG_Widget_Repeaters' );
	register_widget( 'CRRG_Widget_Net_Schedule' );
	register_widget( 'CRRG_Widget_CTA' );
	register_widget( 'CRRG_Widget_Social' );
	register_widget( 'CRRG_Widget_Recent_Posts' );
	register_widget( 'CRRG_Widget_Contact' );
}
add_action( 'widgets_init', 'crrg_register_widgets' );
