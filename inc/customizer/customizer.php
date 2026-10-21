<?php
/**
 * Customizer: panels, sections, settings and controls.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front page sections that can be reordered.
 *
 * @return array Section key => label.
 */
function crrg_front_sections() {
	return array(
		'hero'           => __( 'Hero banner', 'crrg' ),
		'info'           => __( 'Info bar', 'crrg' ),
		'features'       => __( 'Feature cards', 'crrg' ),
		'widgets_top'    => __( 'Widget area: Front Page Top', 'crrg' ),
		'content'        => __( 'Page content', 'crrg' ),
		'posts'          => __( 'Latest posts', 'crrg' ),
		'cta'            => __( 'Call to action', 'crrg' ),
		'widgets_bottom' => __( 'Widget area: Front Page Bottom', 'crrg' ),
	);
}

/**
 * Content layouts.
 *
 * @param bool $with_default Include a "use default" choice (for per-post overrides).
 * @return array
 */
function crrg_layout_choices( $with_default = false ) {
	$choices = array(
		'right-sidebar' => __( 'Right sidebar', 'crrg' ),
		'left-sidebar'  => __( 'Left sidebar', 'crrg' ),
		'both-sidebars' => __( 'Sidebars on both sides', 'crrg' ),
		'no-sidebar'    => __( 'No sidebar (centred content)', 'crrg' ),
		'full-width'    => __( 'Full width (edge to edge)', 'crrg' ),
	);

	if ( $with_default ) {
		$choices = array( 'default' => __( 'Default (from Customizer)', 'crrg' ) ) + $choices;
	}

	return $choices;
}

/**
 * Register a setting and its control in one call.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 * @param string               $id           Option key (must exist in the defaults).
 * @param array                $control      Control args. 'type' defaults to 'text'.
 *                                           Use 'type' => 'color' for colour pickers
 *                                           and 'type' => 'image' for image uploads.
 * @param array                $setting      Optional extra setting args.
 */
function crrg_add_option( $wp_customize, $id, $control, $setting = array() ) {
	$defaults = crrg_option_defaults();
	$type     = isset( $control['type'] ) ? $control['type'] : 'text';

	// Headings are purely visual and store nothing.
	if ( 'heading' === $type ) {
		unset( $control['type'] );
		$control['settings'] = array();
		$wp_customize->add_control( new CRRG_Heading_Control( $wp_customize, $id, $control ) );
		return;
	}

	switch ( $type ) {
		case 'checkbox':
			$sanitize = 'crrg_sanitize_checkbox';
			break;
		case 'select':
		case 'radio':
			$sanitize = 'crrg_sanitize_choice';
			break;
		case 'number':
		case 'range':
			$sanitize = 'crrg_sanitize_number';
			break;
		case 'color':
			$sanitize = 'crrg_sanitize_hex';
			break;
		case 'url':
		case 'image':
			$sanitize = 'crrg_sanitize_url';
			break;
		case 'dropdown-categories':
			$sanitize = 'absint';
			break;
		case 'textarea':
			$sanitize = 'crrg_sanitize_html';
			break;
		default:
			$sanitize = 'sanitize_text_field';
	}

	$wp_customize->add_setting(
		$id,
		array_merge(
			array(
				'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : '',
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			),
			$setting
		)
	);

	$control = array_merge( array( 'settings' => $id ), $control );

	if ( 'color' === $type ) {
		unset( $control['type'] );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, $control ) );
	} elseif ( 'image' === $type ) {
		unset( $control['type'] );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, $control ) );
	} elseif ( 'dropdown-categories' === $type ) {
		$control['type']    = 'select';
		$control['choices'] = crrg_category_choices();
		$wp_customize->add_control( $id, $control );
	} elseif ( 'sortable' === $type ) {
		unset( $control['type'] );
		$wp_customize->add_control( new CRRG_Sortable_Control( $wp_customize, $id, $control ) );
	} else {
		$wp_customize->add_control( $id, $control );
	}
}

/**
 * Category choices for selects.
 *
 * @return array
 */
function crrg_category_choices() {
	$choices = array( 0 => __( 'All categories', 'crrg' ) );

	foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
		$choices[ $category->term_id ] = $category->name;
	}

	return $choices;
}

/**
 * Register Customizer options.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_register( $wp_customize ) {
	require_once CRRG_DIR . '/inc/customizer/controls/class-crrg-heading-control.php';
	require_once CRRG_DIR . '/inc/customizer/controls/class-crrg-sortable-control.php';

	// Live preview for core settings.
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => static function () {
					bloginfo( 'name' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => static function () {
					bloginfo( 'description' );
				},
			)
		);
	}

	$wp_customize->add_panel(
		'crrg_options',
		array(
			'title'       => __( 'CRRG Theme Options', 'crrg' ),
			'description' => __( 'Header, layout, sidebars, blog, front page, footer and social settings.', 'crrg' ),
			'priority'    => 25,
		)
	);

	crrg_customize_site_identity( $wp_customize );
	crrg_customize_colors( $wp_customize );
	crrg_customize_typography( $wp_customize );
	crrg_customize_header( $wp_customize );
	crrg_customize_layout( $wp_customize );
	crrg_customize_page_header( $wp_customize );
	crrg_customize_blog( $wp_customize );
	crrg_customize_single( $wp_customize );
	crrg_customize_front_page( $wp_customize );
	crrg_customize_footer( $wp_customize );
	crrg_customize_social( $wp_customize );
}
add_action( 'customize_register', 'crrg_customize_register' );

/**
 * Site identity extras.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_site_identity( $wp_customize ) {
	crrg_add_option(
		$wp_customize,
		'logo_max_height',
		array(
			'label'       => __( 'Logo maximum height (px)', 'crrg' ),
			'section'     => 'title_tagline',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 24,
				'max'  => 200,
				'step' => 2,
			),
			'priority'    => 9,
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'show_site_title',
		array(
			'label'    => __( 'Display site title', 'crrg' ),
			'section'  => 'title_tagline',
			'type'     => 'checkbox',
			'priority' => 11,
		)
	);
	crrg_add_option(
		$wp_customize,
		'show_tagline',
		array(
			'label'    => __( 'Display tagline', 'crrg' ),
			'section'  => 'title_tagline',
			'type'     => 'checkbox',
			'priority' => 12,
		)
	);
	crrg_add_option(
		$wp_customize,
		'header_callsign',
		array(
			'label'       => __( 'Club call sign', 'crrg' ),
			'description' => __( 'Shown as a badge next to the site title. Leave empty to hide.', 'crrg' ),
			'section'     => 'title_tagline',
			'priority'    => 13,
		),
		array( 'transport' => 'postMessage' )
	);
}

/**
 * Colours.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_colors( $wp_customize ) {
	$wp_customize->get_section( 'colors' )->title       = __( 'Colors', 'crrg' );
	$wp_customize->get_section( 'colors' )->description = __( 'Pick a preset to fill every colour at once, then fine-tune any individual colour below.', 'crrg' );

	$presets = array( 'custom' => __( 'Custom', 'crrg' ) ) + wp_list_pluck( crrg_color_presets(), 'label' );

	crrg_add_option(
		$wp_customize,
		'color_preset',
		array(
			'label'    => __( 'Colour preset', 'crrg' ),
			'section'  => 'colors',
			'type'     => 'select',
			'choices'  => $presets,
			'priority' => 1,
		)
	);

	$groups = array(
		'heading_brand'          => __( 'Brand', 'crrg' ),
		'primary_color'          => __( 'Primary (header, headings accents)', 'crrg' ),
		'secondary_color'        => __( 'Secondary (buttons, calls to action)', 'crrg' ),
		'accent_color'           => __( 'Accent (highlights, badges, underlines)', 'crrg' ),
		'heading_content'        => __( 'Content', 'crrg' ),
		'body_bg_color'          => __( 'Page background', 'crrg' ),
		'surface_color'          => __( 'Card / content background', 'crrg' ),
		'text_color'             => __( 'Body text', 'crrg' ),
		'heading_color'          => __( 'Headings', 'crrg' ),
		'link_color'             => __( 'Links', 'crrg' ),
		'link_hover_color'       => __( 'Links on hover', 'crrg' ),
		'heading_header'         => __( 'Top bar & header', 'crrg' ),
		'topbar_bg_color'        => __( 'Top bar background', 'crrg' ),
		'topbar_text_color'      => __( 'Top bar text', 'crrg' ),
		'header_bg_color'        => __( 'Header background', 'crrg' ),
		'header_text_color'      => __( 'Header text & menu links', 'crrg' ),
		'heading_footer'         => __( 'Footer', 'crrg' ),
		'footer_bg_color'        => __( 'Footer background', 'crrg' ),
		'footer_text_color'      => __( 'Footer text', 'crrg' ),
		'footer_heading_color'   => __( 'Footer headings', 'crrg' ),
		'footer_bottom_bg_color' => __( 'Footer bottom bar background', 'crrg' ),
		'heading_hero'           => __( 'Hero', 'crrg' ),
		'hero_overlay_color'     => __( 'Hero overlay colour', 'crrg' ),
	);

	$priority = 10;
	foreach ( $groups as $id => $label ) {
		$priority += 2;
		if ( 0 === strpos( $id, 'heading_' ) ) {
			crrg_add_option(
				$wp_customize,
				'crrg_' . $id,
				array(
					'label'    => $label,
					'section'  => 'colors',
					'type'     => 'heading',
					'priority' => $priority,
				)
			);
			continue;
		}
		crrg_add_option(
			$wp_customize,
			$id,
			array(
				'label'    => $label,
				'section'  => 'colors',
				'type'     => 'color',
				'priority' => $priority,
			),
			array( 'transport' => 'postMessage' )
		);
	}

	crrg_add_option(
		$wp_customize,
		'hero_overlay_opacity',
		array(
			'label'       => __( 'Hero overlay opacity (%)', 'crrg' ),
			'section'     => 'colors',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 5,
			),
			'priority'    => 100,
		),
		array( 'transport' => 'postMessage' )
	);
}

/**
 * Typography.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_typography( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_typography',
		array(
			'title'    => __( 'Typography', 'crrg' ),
			'panel'    => 'crrg_options',
			'priority' => 10,
		)
	);

	crrg_add_option(
		$wp_customize,
		'font_source',
		array(
			'label'       => __( 'Font source', 'crrg' ),
			'description' => __( 'Google Fonts are loaded from fonts.googleapis.com. Choose "System fonts only" to avoid any external request.', 'crrg' ),
			'section'     => 'crrg_typography',
			'type'        => 'radio',
			'choices'     => array(
				'google' => __( 'Google Fonts', 'crrg' ),
				'system' => __( 'System fonts only', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'body_font',
		array(
			'label'   => __( 'Body font', 'crrg' ),
			'section' => 'crrg_typography',
			'type'    => 'select',
			'choices' => crrg_font_choices(),
		)
	);
	crrg_add_option(
		$wp_customize,
		'heading_font',
		array(
			'label'   => __( 'Heading & menu font', 'crrg' ),
			'section' => 'crrg_typography',
			'type'    => 'select',
			'choices' => crrg_font_choices(),
		)
	);
	crrg_add_option(
		$wp_customize,
		'base_font_size',
		array(
			'label'       => __( 'Base font size (px)', 'crrg' ),
			'section'     => 'crrg_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 14,
				'max' => 22,
			),
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'heading_weight',
		array(
			'label'   => __( 'Heading weight', 'crrg' ),
			'section' => 'crrg_typography',
			'type'    => 'select',
			'choices' => array(
				'400' => __( 'Regular (400)', 'crrg' ),
				'500' => __( 'Medium (500)', 'crrg' ),
				'600' => __( 'Semi-bold (600)', 'crrg' ),
				'700' => __( 'Bold (700)', 'crrg' ),
			),
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'heading_uppercase',
		array(
			'label'   => __( 'Uppercase headings', 'crrg' ),
			'section' => 'crrg_typography',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'nav_uppercase',
		array(
			'label'   => __( 'Uppercase menu links & buttons', 'crrg' ),
			'section' => 'crrg_typography',
			'type'    => 'checkbox',
		)
	);
}

/**
 * Header and top bar.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_header( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_header',
		array(
			'title'    => __( 'Header', 'crrg' ),
			'panel'    => 'crrg_options',
			'priority' => 20,
		)
	);

	crrg_add_option(
		$wp_customize,
		'header_layout',
		array(
			'label'   => __( 'Header layout', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'radio',
			'choices' => array(
				'default'  => __( 'Logo left, menu right', 'crrg' ),
				'centered' => __( 'Logo centred, menu below', 'crrg' ),
				'stacked'  => __( 'Logo left with header widget area, menu bar below', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'sticky_header',
		array(
			'label'   => __( 'Sticky header', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'transparent_header_front',
		array(
			'label'   => __( 'Transparent header over the front page hero', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'header_search',
		array(
			'label'   => __( 'Show search button', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'header_social',
		array(
			'label'   => __( 'Show social icons in the header', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'mobile_menu_style',
		array(
			'label'   => __( 'Mobile menu style', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'radio',
			'choices' => array(
				'offcanvas' => __( 'Slide-in panel', 'crrg' ),
				'dropdown'  => __( 'Drop-down below header', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'mobile_breakpoint',
		array(
			'label'       => __( 'Switch to the mobile menu below (px)', 'crrg' ),
			'description' => __( 'Raise this if your menu wraps onto two lines on smaller laptops.', 'crrg' ),
			'section'     => 'crrg_header',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 600,
				'max'  => 1600,
				'step' => 10,
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'crrg_heading_header_cta',
		array(
			'label'   => __( 'Header button', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'heading',
		)
	);
	crrg_add_option(
		$wp_customize,
		'header_cta_text',
		array(
			'label'   => __( 'Button text', 'crrg' ),
			'section' => 'crrg_header',
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'header_cta_url',
		array(
			'label'       => __( 'Button link', 'crrg' ),
			'description' => __( 'Leave empty to hide the button.', 'crrg' ),
			'section'     => 'crrg_header',
			'type'        => 'url',
		)
	);
	crrg_add_option(
		$wp_customize,
		'header_cta_new_tab',
		array(
			'label'   => __( 'Open button link in a new tab', 'crrg' ),
			'section' => 'crrg_header',
			'type'    => 'checkbox',
		)
	);

	// Top bar.
	$wp_customize->add_section(
		'crrg_topbar',
		array(
			'title'    => __( 'Top Bar', 'crrg' ),
			'panel'    => 'crrg_options',
			'priority' => 15,
		)
	);
	crrg_add_option(
		$wp_customize,
		'topbar_enable',
		array(
			'label'   => __( 'Show top bar', 'crrg' ),
			'section' => 'crrg_topbar',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'topbar_text',
		array(
			'label'       => __( 'Top bar text', 'crrg' ),
			'description' => __( 'Basic HTML allowed. Good for net times or a notice.', 'crrg' ),
			'section'     => 'crrg_topbar',
			'type'        => 'textarea',
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'topbar_show_menu',
		array(
			'label'       => __( 'Show the "Top Bar" menu', 'crrg' ),
			'description' => __( 'Assign a menu to the Top Bar location under Menus.', 'crrg' ),
			'section'     => 'crrg_topbar',
			'type'        => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'topbar_show_social',
		array(
			'label'   => __( 'Show social icons', 'crrg' ),
			'section' => 'crrg_topbar',
			'type'    => 'checkbox',
		)
	);
}

/**
 * Layout and sidebars.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_layout( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_layout',
		array(
			'title'       => __( 'Layout & Sidebars', 'crrg' ),
			'description' => __( 'Default layouts per content type. Individual posts and pages can override these in the editor ("CRRG Layout" box) or with a page template.', 'crrg' ),
			'panel'       => 'crrg_options',
			'priority'    => 30,
		)
	);

	crrg_add_option(
		$wp_customize,
		'site_layout',
		array(
			'label'   => __( 'Site style', 'crrg' ),
			'section' => 'crrg_layout',
			'type'    => 'radio',
			'choices' => array(
				'wide'  => __( 'Wide (full-width backgrounds)', 'crrg' ),
				'boxed' => __( 'Boxed (site framed on the page background)', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'container_width',
		array(
			'label'       => __( 'Content width (px)', 'crrg' ),
			'section'     => 'crrg_layout',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1680,
				'step' => 10,
			),
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'sidebar_width',
		array(
			'label'       => __( 'Sidebar width (px)', 'crrg' ),
			'section'     => 'crrg_layout',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 220,
				'max'  => 440,
				'step' => 10,
			),
		),
		array( 'transport' => 'postMessage' )
	);

	$contexts = array(
		'layout_blog'    => __( 'Blog (posts page)', 'crrg' ),
		'layout_archive' => __( 'Archives (categories, tags, dates, authors)', 'crrg' ),
		'layout_search'  => __( 'Search results', 'crrg' ),
		'layout_single'  => __( 'Single posts', 'crrg' ),
		'layout_page'    => __( 'Pages', 'crrg' ),
	);
	foreach ( $contexts as $id => $label ) {
		crrg_add_option(
			$wp_customize,
			$id,
			array(
				'label'   => $label,
				'section' => 'crrg_layout',
				'type'    => 'select',
				'choices' => crrg_layout_choices(),
			)
		);
	}

	crrg_add_option(
		$wp_customize,
		'sticky_sidebar',
		array(
			'label'   => __( 'Sticky sidebars on desktop', 'crrg' ),
			'section' => 'crrg_layout',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'card_style',
		array(
			'label'   => __( 'Card & widget style', 'crrg' ),
			'section' => 'crrg_layout',
			'type'    => 'radio',
			'choices' => array(
				'shadow'   => __( 'Soft shadow', 'crrg' ),
				'bordered' => __( 'Bordered', 'crrg' ),
				'flat'     => __( 'Flat', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'border_radius',
		array(
			'label'       => __( 'Corner radius (px)', 'crrg' ),
			'section'     => 'crrg_layout',
			'type'        => 'range',
			'input_attrs' => array(
				'min' => 0,
				'max' => 24,
			),
		),
		array( 'transport' => 'postMessage' )
	);
}

/**
 * Page header (title banner) and breadcrumbs.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_page_header( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_page_header',
		array(
			'title'       => __( 'Page Title Banner', 'crrg' ),
			'description' => __( 'The banner with the title and breadcrumbs shown at the top of inner pages, posts and archives.', 'crrg' ),
			'panel'       => 'crrg_options',
			'priority'    => 35,
		)
	);

	crrg_add_option(
		$wp_customize,
		'page_header_enable',
		array(
			'label'       => __( 'Show the title banner', 'crrg' ),
			'description' => __( 'When off, titles are shown inside the content area instead.', 'crrg' ),
			'section'     => 'crrg_page_header',
			'type'        => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'page_header_size',
		array(
			'label'   => __( 'Banner height', 'crrg' ),
			'section' => 'crrg_page_header',
			'type'    => 'select',
			'choices' => array(
				'small'  => __( 'Small', 'crrg' ),
				'medium' => __( 'Medium', 'crrg' ),
				'large'  => __( 'Large', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'page_header_align',
		array(
			'label'   => __( 'Text alignment', 'crrg' ),
			'section' => 'crrg_page_header',
			'type'    => 'radio',
			'choices' => array(
				'left'   => __( 'Left', 'crrg' ),
				'center' => __( 'Centre', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'page_header_image',
		array(
			'label'   => __( 'Default background image', 'crrg' ),
			'section' => 'crrg_page_header',
			'type'    => 'image',
		)
	);
	crrg_add_option(
		$wp_customize,
		'page_header_featured',
		array(
			'label'   => __( 'Use the featured image as the banner background on pages', 'crrg' ),
			'section' => 'crrg_page_header',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'breadcrumbs_enable',
		array(
			'label'       => __( 'Show breadcrumbs', 'crrg' ),
			'description' => __( 'Uses Yoast SEO, Rank Math or SEOPress breadcrumbs when one of them is active.', 'crrg' ),
			'section'     => 'crrg_page_header',
			'type'        => 'checkbox',
		)
	);
}

/**
 * Blog and archives.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_blog( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_blog',
		array(
			'title'    => __( 'Blog & Archives', 'crrg' ),
			'panel'    => 'crrg_options',
			'priority' => 40,
		)
	);

	crrg_add_option(
		$wp_customize,
		'blog_style',
		array(
			'label'   => __( 'Post list style', 'crrg' ),
			'section' => 'crrg_blog',
			'type'    => 'radio',
			'choices' => array(
				'list'    => __( 'List (image beside text)', 'crrg' ),
				'classic' => __( 'Classic (large image above text)', 'crrg' ),
				'grid'    => __( 'Grid of cards', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'blog_columns',
		array(
			'label'   => __( 'Grid columns', 'crrg' ),
			'section' => 'crrg_blog',
			'type'    => 'select',
			'choices' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'excerpt_length',
		array(
			'label'       => __( 'Excerpt length (words)', 'crrg' ),
			'section'     => 'crrg_blog',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 0,
				'max' => 120,
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'read_more_text',
		array(
			'label'   => __( '"Read more" text', 'crrg' ),
			'section' => 'crrg_blog',
		)
	);
	crrg_add_option(
		$wp_customize,
		'archive_thumbnail',
		array(
			'label'   => __( 'Show featured images', 'crrg' ),
			'section' => 'crrg_blog',
			'type'    => 'checkbox',
		)
	);

	$meta = array(
		'meta_date'       => __( 'Show date', 'crrg' ),
		'meta_author'     => __( 'Show author', 'crrg' ),
		'meta_categories' => __( 'Show categories', 'crrg' ),
		'meta_comments'   => __( 'Show comment count', 'crrg' ),
	);
	foreach ( $meta as $id => $label ) {
		crrg_add_option(
			$wp_customize,
			$id,
			array(
				'label'   => $label,
				'section' => 'crrg_blog',
				'type'    => 'checkbox',
			)
		);
	}

	crrg_add_option(
		$wp_customize,
		'pagination_type',
		array(
			'label'   => __( 'Pagination', 'crrg' ),
			'section' => 'crrg_blog',
			'type'    => 'radio',
			'choices' => array(
				'numbers'   => __( 'Numbered', 'crrg' ),
				'prev-next' => __( 'Older / newer links', 'crrg' ),
			),
		)
	);
}

/**
 * Single posts.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_single( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_single',
		array(
			'title'    => __( 'Single Posts', 'crrg' ),
			'panel'    => 'crrg_options',
			'priority' => 45,
		)
	);

	$toggles = array(
		'single_thumbnail'  => __( 'Show featured image', 'crrg' ),
		'single_meta'       => __( 'Show post meta (date, author, categories)', 'crrg' ),
		'single_tags'       => __( 'Show tags', 'crrg' ),
		'single_author_box' => __( 'Show author box', 'crrg' ),
		'single_post_nav'   => __( 'Show previous / next post links', 'crrg' ),
		'single_related'    => __( 'Show related posts', 'crrg' ),
	);
	foreach ( $toggles as $id => $label ) {
		crrg_add_option(
			$wp_customize,
			$id,
			array(
				'label'   => $label,
				'section' => 'crrg_single',
				'type'    => 'checkbox',
			)
		);
	}

	crrg_add_option(
		$wp_customize,
		'single_related_count',
		array(
			'label'   => __( 'Number of related posts', 'crrg' ),
			'section' => 'crrg_single',
			'type'    => 'select',
			'choices' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		)
	);
}

/**
 * Front page sections.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_front_page( $wp_customize ) {
	$wp_customize->add_panel(
		'crrg_front_page',
		array(
			'title'       => __( 'Front Page Sections', 'crrg' ),
			'description' => __( 'Sections shown on the site front page. Works with both "Your latest posts" and a static front page (Settings &rarr; Reading).', 'crrg' ),
			'priority'    => 26,
		)
	);

	// Order.
	$wp_customize->add_section(
		'crrg_front_order',
		array(
			'title'    => __( 'Section Order', 'crrg' ),
			'panel'    => 'crrg_front_page',
			'priority' => 1,
		)
	);
	crrg_add_option(
		$wp_customize,
		'front_sections_order',
		array(
			'label'       => __( 'Drag to reorder the front page sections', 'crrg' ),
			'description' => __( 'Turn individual sections on or off in their own panels.', 'crrg' ),
			'section'     => 'crrg_front_order',
			'type'        => 'sortable',
			'choices'     => crrg_front_sections(),
		),
		array( 'sanitize_callback' => 'crrg_sanitize_sections_order' )
	);

	// Hero.
	$wp_customize->add_section(
		'crrg_hero',
		array(
			'title'    => __( 'Hero Banner', 'crrg' ),
			'panel'    => 'crrg_front_page',
			'priority' => 10,
		)
	);
	crrg_add_option(
		$wp_customize,
		'hero_enable',
		array(
			'label'   => __( 'Show hero banner', 'crrg' ),
			'section' => 'crrg_hero',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'hero_title',
		array(
			'label'   => __( 'Heading', 'crrg' ),
			'section' => 'crrg_hero',
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'hero_text',
		array(
			'label'   => __( 'Text', 'crrg' ),
			'section' => 'crrg_hero',
			'type'    => 'textarea',
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'hero_image',
		array(
			'label'       => __( 'Background image', 'crrg' ),
			'description' => __( 'Recommended at least 1920 &times; 900 px. Without an image a radio-wave pattern is used.', 'crrg' ),
			'section'     => 'crrg_hero',
			'type'        => 'image',
		)
	);
	crrg_add_option(
		$wp_customize,
		'hero_height',
		array(
			'label'   => __( 'Height', 'crrg' ),
			'section' => 'crrg_hero',
			'type'    => 'select',
			'choices' => array(
				'small'  => __( 'Small', 'crrg' ),
				'medium' => __( 'Medium', 'crrg' ),
				'large'  => __( 'Large', 'crrg' ),
				'screen' => __( 'Full screen', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'hero_align',
		array(
			'label'   => __( 'Text alignment', 'crrg' ),
			'section' => 'crrg_hero',
			'type'    => 'radio',
			'choices' => array(
				'left'   => __( 'Left', 'crrg' ),
				'center' => __( 'Centre', 'crrg' ),
			),
		)
	);
	foreach ( array( 1, 2 ) as $n ) {
		crrg_add_option(
			$wp_customize,
			"hero_btn{$n}_text",
			array(
				/* translators: %d: button number. */
				'label'   => sprintf( __( 'Button %d text', 'crrg' ), $n ),
				'section' => 'crrg_hero',
			)
		);
		crrg_add_option(
			$wp_customize,
			"hero_btn{$n}_url",
			array(
				/* translators: %d: button number. */
				'label'   => sprintf( __( 'Button %d link', 'crrg' ), $n ),
				'section' => 'crrg_hero',
				'type'    => 'url',
			)
		);
	}

	// Info bar.
	$wp_customize->add_section(
		'crrg_info',
		array(
			'title'       => __( 'Info Bar', 'crrg' ),
			'description' => __( 'A strip of up to four quick facts under the hero, such as net times and frequencies. Items with an empty title are hidden.', 'crrg' ),
			'panel'       => 'crrg_front_page',
			'priority'    => 20,
		)
	);
	crrg_add_option(
		$wp_customize,
		'info_enable',
		array(
			'label'   => __( 'Show info bar', 'crrg' ),
			'section' => 'crrg_info',
			'type'    => 'checkbox',
		)
	);
	for ( $n = 1; $n <= 4; $n++ ) {
		crrg_add_option(
			$wp_customize,
			"crrg_heading_info_{$n}",
			array(
				/* translators: %d: item number. */
				'label'   => sprintf( __( 'Item %d', 'crrg' ), $n ),
				'section' => 'crrg_info',
				'type'    => 'heading',
			)
		);
		crrg_add_option(
			$wp_customize,
			"info_{$n}_icon",
			array(
				'label'   => __( 'Icon', 'crrg' ),
				'section' => 'crrg_info',
				'type'    => 'select',
				'choices' => crrg_feature_icon_choices(),
			)
		);
		crrg_add_option(
			$wp_customize,
			"info_{$n}_title",
			array(
				'label'   => __( 'Title', 'crrg' ),
				'section' => 'crrg_info',
			)
		);
		crrg_add_option(
			$wp_customize,
			"info_{$n}_text",
			array(
				'label'   => __( 'Text', 'crrg' ),
				'section' => 'crrg_info',
			),
			array( 'sanitize_callback' => 'crrg_sanitize_html' )
		);
	}

	// Feature cards.
	$wp_customize->add_section(
		'crrg_features',
		array(
			'title'       => __( 'Feature Cards', 'crrg' ),
			'description' => __( 'Up to four cards. Cards with an empty title are hidden.', 'crrg' ),
			'panel'       => 'crrg_front_page',
			'priority'    => 30,
		)
	);
	crrg_add_option(
		$wp_customize,
		'features_enable',
		array(
			'label'   => __( 'Show feature cards', 'crrg' ),
			'section' => 'crrg_features',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'features_title',
		array(
			'label'   => __( 'Section heading', 'crrg' ),
			'section' => 'crrg_features',
		)
	);
	crrg_add_option(
		$wp_customize,
		'features_subtitle',
		array(
			'label'   => __( 'Section intro', 'crrg' ),
			'section' => 'crrg_features',
			'type'    => 'textarea',
		)
	);
	crrg_add_option(
		$wp_customize,
		'features_columns',
		array(
			'label'   => __( 'Columns', 'crrg' ),
			'section' => 'crrg_features',
			'type'    => 'select',
			'choices' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'features_link_text',
		array(
			'label'   => __( 'Card link text', 'crrg' ),
			'section' => 'crrg_features',
		)
	);
	for ( $n = 1; $n <= 4; $n++ ) {
		crrg_add_option(
			$wp_customize,
			"crrg_heading_feature_{$n}",
			array(
				/* translators: %d: card number. */
				'label'   => sprintf( __( 'Card %d', 'crrg' ), $n ),
				'section' => 'crrg_features',
				'type'    => 'heading',
			)
		);
		crrg_add_option(
			$wp_customize,
			"feature_{$n}_icon",
			array(
				'label'   => __( 'Icon', 'crrg' ),
				'section' => 'crrg_features',
				'type'    => 'select',
				'choices' => crrg_feature_icon_choices(),
			)
		);
		crrg_add_option(
			$wp_customize,
			"feature_{$n}_title",
			array(
				'label'   => __( 'Title', 'crrg' ),
				'section' => 'crrg_features',
			)
		);
		crrg_add_option(
			$wp_customize,
			"feature_{$n}_text",
			array(
				'label'   => __( 'Text', 'crrg' ),
				'section' => 'crrg_features',
				'type'    => 'textarea',
			)
		);
		crrg_add_option(
			$wp_customize,
			"feature_{$n}_url",
			array(
				'label'   => __( 'Link', 'crrg' ),
				'section' => 'crrg_features',
				'type'    => 'url',
			)
		);
	}

	// Page content.
	$wp_customize->add_section(
		'crrg_front_content',
		array(
			'title'       => __( 'Page Content', 'crrg' ),
			'description' => __( 'With a static front page this is the content of that page. With "Your latest posts" it is the list of posts.', 'crrg' ),
			'panel'       => 'crrg_front_page',
			'priority'    => 40,
		)
	);
	crrg_add_option(
		$wp_customize,
		'front_content_enable',
		array(
			'label'   => __( 'Show page content / posts', 'crrg' ),
			'section' => 'crrg_front_content',
			'type'    => 'checkbox',
		)
	);

	// Latest posts.
	$wp_customize->add_section(
		'crrg_front_posts',
		array(
			'title'    => __( 'Latest Posts', 'crrg' ),
			'panel'    => 'crrg_front_page',
			'priority' => 50,
		)
	);
	crrg_add_option(
		$wp_customize,
		'front_posts_enable',
		array(
			'label'       => __( 'Show latest posts', 'crrg' ),
			'description' => __( 'Only shown with a static front page; with "Your latest posts" the page content section already lists posts.', 'crrg' ),
			'section'     => 'crrg_front_posts',
			'type'        => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'front_posts_title',
		array(
			'label'   => __( 'Section heading', 'crrg' ),
			'section' => 'crrg_front_posts',
		)
	);
	crrg_add_option(
		$wp_customize,
		'front_posts_count',
		array(
			'label'   => __( 'Number of posts', 'crrg' ),
			'section' => 'crrg_front_posts',
			'type'    => 'select',
			'choices' => array(
				'3' => '3',
				'4' => '4',
				'6' => '6',
				'8' => '8',
				'9' => '9',
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'front_posts_category',
		array(
			'label'   => __( 'Category', 'crrg' ),
			'section' => 'crrg_front_posts',
			'type'    => 'dropdown-categories',
		)
	);

	// CTA.
	$wp_customize->add_section(
		'crrg_cta',
		array(
			'title'    => __( 'Call to Action', 'crrg' ),
			'panel'    => 'crrg_front_page',
			'priority' => 60,
		)
	);
	crrg_add_option(
		$wp_customize,
		'cta_enable',
		array(
			'label'   => __( 'Show call to action band', 'crrg' ),
			'section' => 'crrg_cta',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'cta_title',
		array(
			'label'   => __( 'Heading', 'crrg' ),
			'section' => 'crrg_cta',
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'cta_text',
		array(
			'label'   => __( 'Text', 'crrg' ),
			'section' => 'crrg_cta',
			'type'    => 'textarea',
		),
		array( 'transport' => 'postMessage' )
	);
	crrg_add_option(
		$wp_customize,
		'cta_btn_text',
		array(
			'label'   => __( 'Button text', 'crrg' ),
			'section' => 'crrg_cta',
		)
	);
	crrg_add_option(
		$wp_customize,
		'cta_btn_url',
		array(
			'label'   => __( 'Button link', 'crrg' ),
			'section' => 'crrg_cta',
			'type'    => 'url',
		)
	);
}

/**
 * Footer.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_footer( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_footer',
		array(
			'title'    => __( 'Footer', 'crrg' ),
			'panel'    => 'crrg_options',
			'priority' => 60,
		)
	);

	crrg_add_option(
		$wp_customize,
		'footer_columns',
		array(
			'label'       => __( 'Footer widget columns', 'crrg' ),
			'description' => __( 'Add widgets to the Footer 1&ndash;4 widget areas. Set to 0 to hide the widget area.', 'crrg' ),
			'section'     => 'crrg_footer',
			'type'        => 'select',
			'choices'     => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'footer_columns_layout',
		array(
			'label'   => __( 'Column widths', 'crrg' ),
			'section' => 'crrg_footer',
			'type'    => 'radio',
			'choices' => array(
				'equal'      => __( 'Equal', 'crrg' ),
				'wide-first' => __( 'First column wider', 'crrg' ),
			),
		)
	);
	crrg_add_option(
		$wp_customize,
		'footer_copyright',
		array(
			'label'       => __( 'Copyright text', 'crrg' ),
			'description' => __( 'Basic HTML allowed. {year} and {site} are replaced with the current year and site title.', 'crrg' ),
			'section'     => 'crrg_footer',
			'type'        => 'textarea',
		)
	);
	crrg_add_option(
		$wp_customize,
		'footer_menu',
		array(
			'label'   => __( 'Show the "Footer" menu', 'crrg' ),
			'section' => 'crrg_footer',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'footer_social',
		array(
			'label'   => __( 'Show social icons', 'crrg' ),
			'section' => 'crrg_footer',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'footer_credit',
		array(
			'label'   => __( 'Show "Powered by WordPress" credit', 'crrg' ),
			'section' => 'crrg_footer',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'back_to_top',
		array(
			'label'   => __( 'Show "back to top" button', 'crrg' ),
			'section' => 'crrg_footer',
			'type'    => 'checkbox',
		)
	);
}

/**
 * Social links.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function crrg_customize_social( $wp_customize ) {
	$wp_customize->add_section(
		'crrg_social',
		array(
			'title'       => __( 'Social Links', 'crrg' ),
			'description' => __( 'Icons are shown for every network with a link. Choose where they appear in the Top Bar, Header and Footer sections, or with the "CRRG: Social Icons" widget.', 'crrg' ),
			'panel'       => 'crrg_options',
			'priority'    => 70,
		)
	);

	foreach ( crrg_social_networks() as $network => $label ) {
		$is_email = ( 'email' === $network );
		crrg_add_option(
			$wp_customize,
			'social_' . $network,
			array(
				'label'   => $is_email ? __( 'Email address', 'crrg' ) : $label,
				'section' => 'crrg_social',
				'type'    => $is_email ? 'email' : 'url',
			),
			$is_email ? array( 'sanitize_callback' => 'crrg_sanitize_email_link' ) : array()
		);
	}

	crrg_add_option(
		$wp_customize,
		'social_rss',
		array(
			'label'   => __( 'Show RSS feed icon', 'crrg' ),
			'section' => 'crrg_social',
			'type'    => 'checkbox',
		)
	);
	crrg_add_option(
		$wp_customize,
		'social_new_tab',
		array(
			'label'   => __( 'Open social links in a new tab', 'crrg' ),
			'section' => 'crrg_social',
			'type'    => 'checkbox',
		)
	);
}

/**
 * Customizer preview script.
 */
function crrg_customize_preview_js() {
	wp_enqueue_script(
		'crrg-customizer-preview',
		CRRG_URI . '/assets/js/customizer-preview.js',
		array( 'customize-preview' ),
		CRRG_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'crrg_customize_preview_js' );

/**
 * Customizer controls script and styles.
 */
function crrg_customize_controls_assets() {
	wp_enqueue_style(
		'crrg-customizer-controls',
		CRRG_URI . '/assets/css/customizer-controls.css',
		array(),
		CRRG_VERSION
	);
	wp_enqueue_script(
		'crrg-customizer-controls',
		CRRG_URI . '/assets/js/customizer-controls.js',
		array( 'customize-controls', 'jquery', 'jquery-ui-sortable' ),
		CRRG_VERSION,
		true
	);

	$presets = crrg_color_presets();
	foreach ( $presets as $slug => $preset ) {
		unset( $presets[ $slug ]['label'] );
	}
	wp_localize_script( 'crrg-customizer-controls', 'crrgPresets', $presets );
}
add_action( 'customize_controls_enqueue_scripts', 'crrg_customize_controls_assets' );
