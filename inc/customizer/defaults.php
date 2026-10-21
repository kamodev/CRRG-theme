<?php
/**
 * Default values for every theme option, plus colour presets.
 *
 * All options are stored as theme mods. Always read them through
 * crrg_get_option() so the defaults below apply everywhere.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Colour presets offered in the Customizer.
 *
 * Each preset fills every colour setting. "crrg-light" is the default palette.
 *
 * @return array
 */
function crrg_color_presets() {
	// Logo colours: orange #ea6d4b, charcoal #333638 and white. Link text
	// uses a darker shade of the orange so it stays readable on white.
	$logo = array(
		'primary_color'          => '#333638',
		'secondary_color'        => '#ea6d4b',
		'accent_color'           => '#ea6d4b',
		'body_bg_color'          => '#f7f7f6',
		'surface_color'          => '#ffffff',
		'text_color'             => '#3d4043',
		'heading_color'          => '#333638',
		'link_color'             => '#b84a2b',
		'link_hover_color'       => '#333638',
		'topbar_bg_color'        => '#333638',
		'topbar_text_color'      => '#ffffff',
		'header_bg_color'        => '#ffffff',
		'header_text_color'      => '#333638',
		'hero_bg_color'          => '#f6f2ef',
		'banner_bg_color'        => '#f1edea',
		'footer_bg_color'        => '#333638',
		'footer_text_color'      => '#d5d7d9',
		'footer_heading_color'   => '#ffffff',
		'footer_bottom_bg_color' => '#2a2c2e',
		'hero_overlay_color'     => '#333638',
	);

	$presets = array(
		'crrg-light'   => array_merge(
			$logo,
			array( 'label' => __( 'CRRG Light: white header, light hero (default)', 'crrg' ) )
		),
		'crrg-classic' => array_merge(
			$logo,
			array(
				'label'           => __( 'CRRG Classic: white header, charcoal hero and banner', 'crrg' ),
				'body_bg_color'   => '#f4f4f3',
				'hero_bg_color'   => '#333638',
				'banner_bg_color' => '#333638',
			)
		),
		'crrg-dark'    => array_merge(
			$logo,
			array(
				'label'                  => __( 'CRRG Dark: charcoal header and hero', 'crrg' ),
				'body_bg_color'          => '#f4f4f3',
				'topbar_bg_color'        => '#222426',
				'topbar_text_color'      => '#d5d7d9',
				'header_bg_color'        => '#333638',
				'header_text_color'      => '#ffffff',
				'hero_bg_color'          => '#2a2c2e',
				'banner_bg_color'        => '#333638',
				'footer_bg_color'        => '#222426',
				'footer_bottom_bg_color' => '#18191a',
			)
		),
		'crrg-orange'  => array_merge(
			$logo,
			array(
				'label'             => __( 'CRRG Orange: orange top bar, warm light hero', 'crrg' ),
				'topbar_bg_color'   => '#ea6d4b',
				'topbar_text_color' => '#1d1e20',
				'hero_bg_color'     => '#fdf1ec',
				'banner_bg_color'   => '#fbe7df',
			)
		),
		'crrg-mono'    => array_merge(
			$logo,
			array(
				'label'                  => __( 'Black & White: all light, orange buttons only', 'crrg' ),
				'body_bg_color'          => '#ffffff',
				'topbar_bg_color'        => '#f2f2f1',
				'topbar_text_color'      => '#333638',
				'hero_bg_color'          => '#f4f4f3',
				'banner_bg_color'        => '#f4f4f3',
				'footer_bg_color'        => '#f2f2f1',
				'footer_text_color'      => '#4a4d50',
				'footer_heading_color'   => '#333638',
				'footer_bottom_bg_color' => '#e7e7e6',
			)
		),
		'crrg-blue'    => array_merge(
			$logo,
			array(
				'label'                  => __( 'Orange & Blue: blue top bar, links and footer', 'crrg' ),
				'primary_color'          => '#1f4e79',
				'link_color'             => '#1d5fa8',
				'link_hover_color'       => '#b84a2b',
				'topbar_bg_color'        => '#1f4e79',
				'hero_bg_color'          => '#f1f5f9',
				'banner_bg_color'        => '#1f4e79',
				'footer_bg_color'        => '#1b2a3a',
				'footer_text_color'      => '#c7d1dc',
				'footer_bottom_bg_color' => '#142030',
				'hero_overlay_color'     => '#1f4e79',
			)
		),
	);

	/**
	 * Filters the colour presets available in the Customizer.
	 *
	 * @param array $presets Preset slug => settings.
	 */
	return apply_filters( 'crrg_color_presets', $presets );
}

/**
 * All theme option defaults.
 *
 * @return array
 */
function crrg_option_defaults() {
	static $defaults = null;

	if ( null !== $defaults ) {
		return $defaults;
	}

	$colors = crrg_color_presets()['crrg-light'];
	unset( $colors['label'] );

	$defaults = array_merge(
		$colors,
		array(
			// Colours.
			'color_preset'             => 'crrg-light',
			'hero_overlay_opacity'     => 70,

			// Typography.
			'font_source'              => 'google',
			'body_font'                => 'source-sans-3',
			'heading_font'             => 'oswald',
			'base_font_size'           => 17,
			'heading_weight'           => '600',
			'heading_uppercase'        => true,
			'nav_uppercase'            => true,

			// Header.
			'header_layout'            => 'default',
			'sticky_header'            => true,
			'transparent_header_front' => false,
			'header_search'            => true,
			'header_social'            => false,
			'show_site_title'          => false,
			'show_tagline'             => false,
			'header_callsign'          => '',
			'logo_max_height'          => 88,
			'bundled_logo'             => true,
			'header_cta_text'          => __( 'Join the Club', 'crrg' ),
			'header_cta_url'           => '/membership/',
			'header_cta_new_tab'       => false,
			'mobile_menu_style'        => 'offcanvas',
			'mobile_breakpoint'        => 1200,

			// Top bar.
			'topbar_enable'            => true,
			'topbar_text'              => __( 'Sunday Night Net 7:00 PM MT on 146.670&minus; (PL 100.0)', 'crrg' ),
			'topbar_show_menu'         => true,
			'topbar_show_social'       => true,

			// Layout.
			'site_layout'              => 'wide',
			'container_width'          => 1200,
			'sidebar_width'            => 320,
			'layout_blog'              => 'right-sidebar',
			'layout_archive'           => 'right-sidebar',
			'layout_search'            => 'right-sidebar',
			'layout_single'            => 'right-sidebar',
			'layout_page'              => 'no-sidebar',
			'sticky_sidebar'           => false,
			'border_radius'            => 6,
			'card_style'               => 'shadow',

			// Page header (title banner).
			'page_header_enable'       => true,
			'page_header_image'        => '',
			'page_header_featured'     => true,
			'page_header_align'        => 'left',
			'page_header_size'         => 'medium',
			'breadcrumbs_enable'       => true,

			// Blog & archives.
			'blog_style'               => 'list',
			'blog_columns'             => 2,
			'excerpt_length'           => 30,
			'read_more_text'           => __( 'Read more', 'crrg' ),
			'archive_thumbnail'        => true,
			'meta_date'                => true,
			'meta_author'              => true,
			'meta_categories'          => true,
			'meta_comments'            => true,
			'pagination_type'          => 'numbers',

			// Single post.
			'single_thumbnail'         => true,
			'single_meta'              => true,
			'single_tags'              => true,
			'single_author_box'        => true,
			'single_post_nav'          => true,
			'single_related'           => true,
			'single_related_count'     => 3,

			// Front page.
			'front_sections_order'     => 'hero,info,features,widgets_top,content,posts,cta,widgets_bottom',
			'hero_enable'              => true,
			'hero_title'               => __( 'Castle Rock Repeater Group', 'crrg' ),
			'hero_text'                => __( 'Serving amateur radio operators across the Colorado Front Range since the 1960s. Three linked repeaters, weekly nets, and a welcoming community of hams.', 'crrg' ),
			'hero_image'               => '',
			'hero_height'              => 'large',
			'hero_align'               => 'left',
			'hero_btn1_text'           => __( 'Our Repeaters', 'crrg' ),
			'hero_btn1_url'            => '/repeaters/',
			'hero_btn2_text'           => __( 'Join a Net', 'crrg' ),
			'hero_btn2_url'            => '/nets/',

			'info_enable'              => true,
			'info_1_icon'              => 'radio',
			'info_1_title'             => __( 'Sunday Night Net', 'crrg' ),
			'info_1_text'              => __( '7:00 PM MT &middot; 146.670&minus;', 'crrg' ),
			'info_2_icon'              => 'tower',
			'info_2_title'             => __( 'Three Repeaters', 'crrg' ),
			'info_2_text'              => __( '2 m &amp; 70 cm, PL 100.0 Hz', 'crrg' ),
			'info_3_icon'              => 'digital',
			'info_3_title'             => __( 'Digital Learning Net', 'crrg' ),
			'info_3_text'              => __( 'Saturdays 10:30 AM MT', 'crrg' ),
			'info_4_icon'              => 'users',
			'info_4_title'             => __( 'Everyone Welcome', 'crrg' ),
			'info_4_text'              => __( 'Licensed hams of all levels', 'crrg' ),

			'features_enable'          => true,
			'features_title'           => __( 'What We Do', 'crrg' ),
			'features_subtitle'        => __( 'Keeping the Front Range on the air &mdash; every day and when it matters most.', 'crrg' ),
			'features_columns'         => 4,
			'feature_1_icon'           => 'tower',
			'feature_1_title'          => __( 'Repeaters', 'crrg' ),
			'feature_1_text'           => __( 'Wide-area 2 m and 70 cm coverage from Denver to the Palmer Divide, maintained by club volunteers.', 'crrg' ),
			'feature_1_url'            => '/repeaters/',
			'feature_2_icon'           => 'radio',
			'feature_2_title'          => __( 'Weekly Nets', 'crrg' ),
			'feature_2_text'           => __( 'Check in on the Sunday evening net or learn digital modes on Saturday mornings.', 'crrg' ),
			'feature_2_url'            => '/nets/',
			'feature_3_icon'           => 'shield',
			'feature_3_title'          => __( 'Emergency Readiness', 'crrg' ),
			'feature_3_text'           => __( 'Practise the skills that keep communities connected when phones and power go down.', 'crrg' ),
			'feature_3_url'            => '/about/',
			'feature_4_icon'           => 'users',
			'feature_4_title'          => __( 'Membership', 'crrg' ),
			'feature_4_text'           => __( 'Support the repeaters and join a friendly group of operators from across Colorado.', 'crrg' ),
			'feature_4_url'            => '/membership/',
			'features_link_text'       => __( 'Learn more', 'crrg' ),

			'front_content_enable'     => true,

			'front_posts_enable'       => true,
			'front_posts_title'        => __( 'Latest News', 'crrg' ),
			'front_posts_count'        => 3,
			'front_posts_category'     => 0,

			'cta_enable'               => true,
			'cta_title'                => __( 'Support the Repeaters', 'crrg' ),
			'cta_text'                 => __( 'Membership dues keep our machines on the air for everyone. Become a member today.', 'crrg' ),
			'cta_btn_text'             => __( 'Become a Member', 'crrg' ),
			'cta_btn_url'              => '/membership/',

			// Footer.
			'footer_columns'           => 4,
			'footer_columns_layout'    => 'equal',
			'footer_copyright'         => __( '&copy; {year} {site}. All rights reserved.', 'crrg' ),
			'footer_social'            => true,
			'footer_menu'              => true,
			'footer_credit'            => true,
			'back_to_top'              => true,

			// Social.
			'social_facebook'          => '',
			'social_x'                 => '',
			'social_youtube'           => '',
			'social_instagram'         => '',
			'social_discord'           => '',
			'social_github'            => '',
			'social_linkedin'          => '',
			'social_mastodon'          => '',
			'social_reddit'            => '',
			'social_groupsio'          => '',
			'social_email'             => '',
			'social_rss'               => false,
			'social_new_tab'           => true,
		)
	);

	/**
	 * Filters the theme option defaults.
	 *
	 * @param array $defaults Option key => default value.
	 */
	$defaults = apply_filters( 'crrg_option_defaults', $defaults );

	return $defaults;
}

/**
 * Get a theme option, falling back to its default.
 *
 * @param string $key Option key.
 * @return mixed
 */
function crrg_get_option( $key ) {
	$defaults = crrg_option_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : false;

	// Colours that were never set individually follow the chosen preset.
	if ( 'color_preset' !== $key && ( '_color' === substr( $key, -6 ) ) ) {
		$preset  = get_theme_mod( 'color_preset', $defaults['color_preset'] );
		$presets = crrg_color_presets();
		if ( isset( $presets[ $preset ][ $key ] ) ) {
			$default = $presets[ $preset ][ $key ];
		}
	}

	return get_theme_mod( $key, $default );
}
