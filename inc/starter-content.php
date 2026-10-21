<?php
/**
 * Starter content offered in the Customizer on a fresh site.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render one of the theme's pattern files to block markup.
 *
 * @param string $file Pattern file name without extension.
 * @return string
 */
function crrg_pattern_markup( $file ) {
	$path = CRRG_DIR . '/patterns/' . sanitize_file_name( $file ) . '.php';

	if ( ! file_exists( $path ) ) {
		return '';
	}

	ob_start();
	include $path;

	return trim( ob_get_clean() );
}

/**
 * Register starter content.
 */
function crrg_starter_content() {
	// Starter content is only ever imported on a fresh install.
	if ( ! get_option( 'fresh_site' ) ) {
		return;
	}

	$starter = array(
		'posts'     => array(
			'home'       => array(
				'post_type'    => 'page',
				'post_title'   => _x( 'Home', 'Starter content page title', 'crrg' ),
				'post_content' => '<!-- wp:heading {"className":"is-style-crrg-underline"} --><h2 class="wp-block-heading is-style-crrg-underline">' . esc_html__( 'Welcome', 'crrg' ) . '</h2><!-- /wp:heading -->' . "\n\n" . '<!-- wp:paragraph --><p>' . esc_html__( 'Whether you were licensed last week or have been on the air for decades, you will find a friendly signal on our repeaters. Program them into your radio, check in to a net, and come say hello.', 'crrg' ) . '</p><!-- /wp:paragraph -->' . "\n\n" . crrg_pattern_markup( 'faq' ),
			),
			'about'      => array(
				'post_type'    => 'page',
				'post_title'   => _x( 'About', 'Starter content page title', 'crrg' ),
				'post_name'    => 'about',
				'post_excerpt' => __( 'A Front Range amateur radio club keeping repeaters on the air since the 1960s.', 'crrg' ),
				'post_content' => '<!-- wp:paragraph --><p>' . esc_html__( 'The Castle Rock Repeater Group is an amateur radio club based in the Denver area. Originally founded in the 1960s and organised in its current form in 1993, the group has members throughout the Colorado Front Range and beyond.', 'crrg' ) . '</p><!-- /wp:paragraph -->' . "\n\n" . crrg_pattern_markup( 'feature-cards' ),
			),
			'repeaters'  => array(
				'post_type'    => 'page',
				'post_title'   => _x( 'Repeaters', 'Starter content page title', 'crrg' ),
				'post_name'    => 'repeaters',
				'post_content' => crrg_pattern_markup( 'repeater-table' ),
			),
			'nets'       => array(
				'post_type'    => 'page',
				'post_title'   => _x( 'Nets', 'Starter content page title', 'crrg' ),
				'post_name'    => 'nets',
				'post_content' => crrg_pattern_markup( 'net-schedule' ),
			),
			'membership' => array(
				'post_type'    => 'page',
				'post_title'   => _x( 'Membership', 'Starter content page title', 'crrg' ),
				'post_name'    => 'membership',
				'post_content' => crrg_pattern_markup( 'membership' ),
			),
			'contact'    => array(
				'post_type'    => 'page',
				'post_title'   => _x( 'Contact', 'Starter content page title', 'crrg' ),
				'post_name'    => 'contact',
				'post_content' => crrg_pattern_markup( 'contact-info' ),
			),
			'blog'       => array(
				'post_type'  => 'page',
				'post_title' => _x( 'News', 'Starter content page title', 'crrg' ),
				'post_name'  => 'news',
			),
		),

		'options'   => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		'nav_menus' => array(
			'primary' => array(
				'name'  => __( 'Primary Menu', 'crrg' ),
				'items' => array(
					'link_home',
					'page_about',
					'page_repeaters'  => array(
						'type'      => 'post_type',
						'object'    => 'page',
						'object_id' => '{{repeaters}}',
					),
					'page_nets'       => array(
						'type'      => 'post_type',
						'object'    => 'page',
						'object_id' => '{{nets}}',
					),
					'page_membership' => array(
						'type'      => 'post_type',
						'object'    => 'page',
						'object_id' => '{{membership}}',
					),
					'page_blog',
					'page_contact',
				),
			),
			'topbar'  => array(
				'name'  => __( 'Top Bar Menu', 'crrg' ),
				'items' => array(
					'page_nets' => array(
						'type'      => 'post_type',
						'object'    => 'page',
						'object_id' => '{{nets}}',
					),
					'page_contact',
				),
			),
			'footer'  => array(
				'name'  => __( 'Footer Menu', 'crrg' ),
				'items' => array(
					'page_about',
					'page_contact',
				),
			),
		),

		'widgets'   => array(
			'sidebar-1' => array(
				'search',
				'crrg_nets' => array(
					'crrg_net_schedule',
					array(
						'title' => __( 'Weekly Nets', 'crrg' ),
					),
				),
				'recent-posts',
				'categories',
			),
			'footer-1'  => array(
				'text_about',
			),
			'footer-2'  => array(
				'crrg_repeaters_footer' => array(
					'crrg_repeaters',
					array(
						'title' => __( 'Repeaters', 'crrg' ),
					),
				),
			),
			'footer-3'  => array(
				'crrg_nets_footer' => array(
					'crrg_net_schedule',
					array(
						'title' => __( 'Nets', 'crrg' ),
					),
				),
			),
			'footer-4'  => array(
				'crrg_contact_footer' => array(
					'crrg_contact',
					array(
						'title' => __( 'Contact', 'crrg' ),
					),
				),
			),
		),
	);

	/**
	 * Filters the starter content.
	 *
	 * @param array $starter Starter content definition.
	 */
	add_theme_support( 'starter-content', apply_filters( 'crrg_starter_content', $starter ) );
}
add_action( 'after_setup_theme', 'crrg_starter_content' );
