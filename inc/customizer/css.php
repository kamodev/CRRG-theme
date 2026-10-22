<?php
/**
 * Dynamic CSS generated from Customizer options.
 *
 * Every option becomes a CSS custom property on :root, and the stylesheet
 * only ever reads those variables. The same block is added to the block
 * editor so content looks the same while editing.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Map of colour options to CSS custom properties.
 *
 * @return array Option key => property name.
 */
function crrg_color_variables() {
	return array(
		'primary_color'          => '--crrg-primary',
		'secondary_color'        => '--crrg-secondary',
		'accent_color'           => '--crrg-accent',
		'body_bg_color'          => '--crrg-body-bg',
		'surface_color'          => '--crrg-surface',
		'text_color'             => '--crrg-text',
		'heading_color'          => '--crrg-heading',
		'link_color'             => '--crrg-link',
		'link_hover_color'       => '--crrg-link-hover',
		'topbar_bg_color'        => '--crrg-topbar-bg',
		'topbar_text_color'      => '--crrg-topbar-text',
		'header_bg_color'        => '--crrg-header-bg',
		'header_text_color'      => '--crrg-header-text',
		'hero_bg_color'          => '--crrg-hero-bg',
		'banner_bg_color'        => '--crrg-banner-bg',
		'footer_bg_color'        => '--crrg-footer-bg',
		'footer_text_color'      => '--crrg-footer-text',
		'footer_heading_color'   => '--crrg-footer-heading',
		'footer_bottom_bg_color' => '--crrg-footer-bottom-bg',
		'hero_overlay_color'     => '--crrg-hero-overlay',
	);
}

/**
 * Pick black or white text for a background colour.
 *
 * @param string $hex Background colour.
 * @return string
 */
function crrg_contrast_color( $hex ) {
	$hex = ltrim( (string) $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) ) {
		return '#ffffff';
	}

	$channels = array_map( 'hexdec', str_split( $hex, 2 ) );
	foreach ( $channels as $i => $c ) {
		$c              = $c / 255;
		$channels[ $i ] = ( $c <= 0.03928 ) ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
	}
	$luminance = 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];

	// Compare contrast against white and against near-black (#111).
	return ( ( 1.05 / ( $luminance + 0.05 ) ) >= ( ( $luminance + 0.05 ) / 0.0556 ) ) ? '#ffffff' : '#111111';
}

/**
 * Whether a colour option holds a dark colour (one that needs light text).
 *
 * @param string $option Colour option key.
 * @return bool
 */
function crrg_is_dark( $option ) {
	return '#ffffff' === crrg_contrast_color( crrg_get_option( $option ) );
}

/**
 * Build the :root custom property block.
 *
 * @return string
 */
function crrg_css_variables() {
	$vars = array();

	foreach ( crrg_color_variables() as $option => $property ) {
		$value = sanitize_hex_color( crrg_get_option( $option ) );
		if ( $value ) {
			$vars[ $property ] = $value;
		}
	}

	// Text colours that sit on brand colours.
	$vars['--crrg-on-primary']   = crrg_contrast_color( crrg_get_option( 'primary_color' ) );
	$vars['--crrg-on-secondary'] = crrg_contrast_color( crrg_get_option( 'secondary_color' ) );
	$vars['--crrg-on-accent']    = crrg_contrast_color( crrg_get_option( 'accent_color' ) );
	$vars['--crrg-on-hero-bg']   = crrg_contrast_color( crrg_get_option( 'hero_bg_color' ) );
	$vars['--crrg-on-banner']    = crrg_contrast_color( crrg_get_option( 'banner_bg_color' ) );
	$vars['--crrg-on-overlay']   = crrg_contrast_color( crrg_get_option( 'hero_overlay_color' ) );

	// Text over the hero: the overlay decides when there is an image.
	$vars['--crrg-hero-fg'] = crrg_get_option( 'hero_image' ) ? $vars['--crrg-on-overlay'] : $vars['--crrg-on-hero-bg'];

	// Accent text needs a lighter orange on dark backgrounds and the darker
	// link colour on light ones to stay readable.
	$on_dark = 'color-mix(in srgb, var(--crrg-accent) 78%, #fff)';

	$vars['--crrg-header-accent'] = crrg_is_dark( 'header_bg_color' ) ? $on_dark : 'var(--crrg-link)';
	$vars['--crrg-banner-accent'] = crrg_is_dark( 'banner_bg_color' ) ? $on_dark : 'var(--crrg-link)';
	$vars['--crrg-footer-accent'] = crrg_is_dark( 'footer_bg_color' ) ? $on_dark : 'var(--crrg-link)';
	$vars['--crrg-hero-accent']   = crrg_is_dark( 'hero_bg_color' ) ? $on_dark : 'var(--crrg-secondary)';

	$vars['--crrg-hero-overlay-opacity'] = max( 0, min( 100, (int) crrg_get_option( 'hero_overlay_opacity' ) ) ) / 100;

	$vars['--crrg-font-body']         = crrg_font_stack( crrg_get_option( 'body_font' ) );
	$vars['--crrg-font-heading']      = crrg_font_stack( crrg_get_option( 'heading_font' ) );
	$vars['--crrg-font-size']         = absint( crrg_get_option( 'base_font_size' ) ) . 'px';
	$vars['--crrg-heading-weight']    = absint( crrg_get_option( 'heading_weight' ) );
	$vars['--crrg-heading-transform'] = crrg_get_option( 'heading_uppercase' ) ? 'uppercase' : 'none';
	$vars['--crrg-nav-transform']     = crrg_get_option( 'nav_uppercase' ) ? 'uppercase' : 'none';

	// Full screen: the container fills the window, less the side padding.
	$full_screen = 'fixed' !== crrg_get_option( 'width_mode' );
	$full_text   = 'readable' !== crrg_get_option( 'content_width_mode' );
	$gutter      = absint( crrg_get_option( 'site_gutter' ) );

	$vars['--crrg-container']      = $full_screen ? '100%' : absint( crrg_get_option( 'container_width' ) ) . 'px';
	$vars['--crrg-readable']       = $full_text ? '100%' : absint( crrg_get_option( 'readable_width' ) ) . 'px';
	$vars['--crrg-gutter-wide']    = $gutter . 'px';
	$vars['--crrg-header-padding'] = absint( crrg_get_option( 'header_padding' ) ) . 'px';
	$vars['--crrg-content-top']    = absint( crrg_get_option( 'content_top_space' ) ) . 'px';
	$vars['--crrg-gutter-mobile']  = min( 20, $gutter ) . 'px';
	$vars['--crrg-sidebar']        = absint( crrg_get_option( 'sidebar_width' ) ) . 'px';
	$vars['--crrg-radius']         = absint( crrg_get_option( 'border_radius' ) ) . 'px';
	$vars['--crrg-logo-height']    = absint( crrg_get_option( 'logo_max_height' ) ) . 'px';
	$vars['--crrg-nav-breakpoint'] = crrg_navigation_breakpoint() . 'px';

	$css = '';
	foreach ( $vars as $property => $value ) {
		$css .= $property . ':' . $value . ';';
	}

	return ':root{' . $css . '}';
}

/**
 * Width at which the desktop menu replaces the mobile menu toggle.
 *
 * @return int
 */
function crrg_navigation_breakpoint() {
	return max( 600, min( 1600, absint( crrg_get_option( 'mobile_breakpoint' ) ) ) );
}

/**
 * Media queries for the desktop / mobile navigation switch.
 *
 * CSS custom properties cannot be used in media queries, so these rules are
 * generated from the "Switch to the mobile menu below" option.
 *
 * @return string
 */
function crrg_css_navigation_breakpoint() {
	$bp = crrg_navigation_breakpoint();

	$desktop = '.main-navigation{display:block}'
		. '.menu-toggle{display:none}'
		. '.site-header__nav{display:block}'
		. '.site-header__widgets{display:flex}'
		. '.mobile-navigation,.mobile-navigation-backdrop{display:none!important}';

	$mobile = '.menu-toggle__label{display:inline}'
		. '.site-header--centered .site-header__top{grid-template-columns:auto 1fr}'
		. '.site-header--centered .site-header__start{display:none}'
		. '.site-header--centered .site-branding{flex-direction:row;text-align:start}'
		. '.site-header--centered .site-branding__text{justify-items:start}'
		. '.site-header .social-links--header{display:none}'
		. '.site-header--right .header-actions,.site-header--right .main-navigation+.header-actions{margin-inline-start:0;margin-inline-end:auto}';

	return '@media (min-width:' . $bp . 'px){' . $desktop . '}'
		. '@media (max-width:' . ( $bp - 1 ) . 'px){' . $mobile . '}'
		. '@media (max-width:420px){.menu-toggle__label{display:none}}';
}

/**
 * Background images set through the Customizer.
 *
 * @return string
 */
function crrg_css_images() {
	$css = '';

	$hero = crrg_get_option( 'hero_image' );
	if ( $hero ) {
		$css .= '.crrg-hero{background-image:url("' . esc_url_raw( $hero ) . '");}';
	}

	$banner = crrg_get_option( 'page_header_image' );
	if ( $banner ) {
		$css .= '.page-banner{background-image:url("' . esc_url_raw( $banner ) . '");}';
	}

	return $css;
}

/**
 * Print the dynamic CSS after the main stylesheet.
 */
function crrg_enqueue_dynamic_css() {
	wp_add_inline_style( 'crrg-theme', crrg_css_variables() . crrg_css_navigation_breakpoint() . crrg_css_images() );
}
add_action( 'wp_enqueue_scripts', 'crrg_enqueue_dynamic_css', 20 );
