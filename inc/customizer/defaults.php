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
 * Each preset fills every colour setting. "colorado" is the default palette.
 *
 * @return array
 */
function crrg_color_presets() {
	$presets = array(
		'colorado' => array(
			'label'                  => __( 'Colorado Navy (default)', 'crrg' ),
			'primary_color'          => '#14304f',
			'secondary_color'        => '#c8102e',
			'accent_color'           => '#f2b705',
			'body_bg_color'          => '#f3f5f8',
			'surface_color'          => '#ffffff',
			'text_color'             => '#2b3440',
			'heading_color'          => '#0e2238',
			'link_color'             => '#1c5d99',
			'link_hover_color'       => '#c8102e',
			'topbar_bg_color'        => '#0b1d31',
			'topbar_text_color'      => '#c9d4e2',
			'header_bg_color'        => '#14304f',
			'header_text_color'      => '#ffffff',
			'footer_bg_color'        => '#0e2238',
			'footer_text_color'      => '#c3cedb',
			'footer_heading_color'   => '#ffffff',
			'footer_bottom_bg_color' => '#081626',
			'hero_overlay_color'     => '#0b1d31',
		),
		'signal'   => array(
			'label'                  => __( 'Signal Orange', 'crrg' ),
			'primary_color'          => '#23272b',
			'secondary_color'        => '#e8720c',
			'accent_color'           => '#ffc857',
			'body_bg_color'          => '#f4f4f2',
			'surface_color'          => '#ffffff',
			'text_color'             => '#2e3236',
			'heading_color'          => '#16191c',
			'link_color'             => '#c25a00',
			'link_hover_color'       => '#23272b',
			'topbar_bg_color'        => '#16191c',
			'topbar_text_color'      => '#c8cbce',
			'header_bg_color'        => '#23272b',
			'header_text_color'      => '#ffffff',
			'footer_bg_color'        => '#1b1e21',
			'footer_text_color'      => '#b9bdc1',
			'footer_heading_color'   => '#ffffff',
			'footer_bottom_bg_color' => '#121416',
			'hero_overlay_color'     => '#16191c',
		),
		'tactical' => array(
			'label'                  => __( 'Tactical Olive', 'crrg' ),
			'primary_color'          => '#2f3527',
			'secondary_color'        => '#b4532a',
			'accent_color'           => '#c9b26b',
			'body_bg_color'          => '#f2f1ec',
			'surface_color'          => '#ffffff',
			'text_color'             => '#33352f',
			'heading_color'          => '#1f231a',
			'link_color'             => '#6b7a3a',
			'link_hover_color'       => '#b4532a',
			'topbar_bg_color'        => '#1f231a',
			'topbar_text_color'      => '#cfd1c4',
			'header_bg_color'        => '#2f3527',
			'header_text_color'      => '#ffffff',
			'footer_bg_color'        => '#23281d',
			'footer_text_color'      => '#c2c4b6',
			'footer_heading_color'   => '#ffffff',
			'footer_bottom_bg_color' => '#181b14',
			'hero_overlay_color'     => '#1f231a',
		),
		'forest'   => array(
			'label'                  => __( 'Front Range Forest', 'crrg' ),
			'primary_color'          => '#1d3b2f',
			'secondary_color'        => '#2f7d4f',
			'accent_color'           => '#e0b23c',
			'body_bg_color'          => '#f2f5f2',
			'surface_color'          => '#ffffff',
			'text_color'             => '#2a332e',
			'heading_color'          => '#14281f',
			'link_color'             => '#2f7d4f',
			'link_hover_color'       => '#1d3b2f',
			'topbar_bg_color'        => '#14281f',
			'topbar_text_color'      => '#c6d3cb',
			'header_bg_color'        => '#1d3b2f',
			'header_text_color'      => '#ffffff',
			'footer_bg_color'        => '#16301f',
			'footer_text_color'      => '#bfcdc4',
			'footer_heading_color'   => '#ffffff',
			'footer_bottom_bg_color' => '#0f2116',
			'hero_overlay_color'     => '#14281f',
		),
		'light'    => array(
			'label'                  => __( 'Clean Light', 'crrg' ),
			'primary_color'          => '#1a4f8b',
			'secondary_color'        => '#d2232a',
			'accent_color'           => '#f2b705',
			'body_bg_color'          => '#ffffff',
			'surface_color'          => '#f6f8fb',
			'text_color'             => '#2b3440',
			'heading_color'          => '#102a43',
			'link_color'             => '#1a4f8b',
			'link_hover_color'       => '#d2232a',
			'topbar_bg_color'        => '#1a4f8b',
			'topbar_text_color'      => '#ffffff',
			'header_bg_color'        => '#ffffff',
			'header_text_color'      => '#102a43',
			'footer_bg_color'        => '#102a43',
			'footer_text_color'      => '#c5d2e0',
			'footer_heading_color'   => '#ffffff',
			'footer_bottom_bg_color' => '#0a1d30',
			'hero_overlay_color'     => '#102a43',
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

	$colors = crrg_color_presets()['colorado'];
	unset( $colors['label'] );

	$defaults = array_merge(
		$colors,
		array(
			// Colours.
			'color_preset'             => 'colorado',
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
			'show_site_title'          => true,
			'show_tagline'             => true,
			'header_callsign'          => '',
			'logo_max_height'          => 64,
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
