<?php
/**
 * Font choices and loading.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Available fonts.
 *
 * "google" is the family query used for the Google Fonts CSS API. Fonts
 * without it are system stacks and never trigger an external request.
 *
 * @return array
 */
function crrg_fonts() {
	$fonts = array(
		'system'           => array(
			'label' => __( 'System UI (no download)', 'crrg' ),
			'stack' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
		),
		'system-serif'     => array(
			'label' => __( 'System Serif (no download)', 'crrg' ),
			'stack' => 'Georgia, Cambria, "Times New Roman", Times, serif',
		),
		'system-condensed' => array(
			'label' => __( 'System Condensed (no download)', 'crrg' ),
			'stack' => '"Arial Narrow", "Roboto Condensed", "Helvetica Neue", Arial, sans-serif',
		),
		'source-sans-3'    => array(
			'label'  => 'Source Sans 3',
			'stack'  => '"Source Sans 3", "Source Sans Pro", Arial, sans-serif',
			'google' => 'Source+Sans+3:ital,wght@0,400;0,600;0,700;1,400',
		),
		'inter'            => array(
			'label'  => 'Inter',
			'stack'  => 'Inter, "Segoe UI", Arial, sans-serif',
			'google' => 'Inter:wght@400;500;600;700',
		),
		'roboto'           => array(
			'label'  => 'Roboto',
			'stack'  => 'Roboto, "Segoe UI", Arial, sans-serif',
			'google' => 'Roboto:ital,wght@0,400;0,500;0,700;1,400',
		),
		'open-sans'        => array(
			'label'  => 'Open Sans',
			'stack'  => '"Open Sans", "Segoe UI", Arial, sans-serif',
			'google' => 'Open+Sans:ital,wght@0,400;0,600;0,700;1,400',
		),
		'lato'             => array(
			'label'  => 'Lato',
			'stack'  => 'Lato, "Segoe UI", Arial, sans-serif',
			'google' => 'Lato:ital,wght@0,400;0,700;1,400',
		),
		'ibm-plex-sans'    => array(
			'label'  => 'IBM Plex Sans',
			'stack'  => '"IBM Plex Sans", "Segoe UI", Arial, sans-serif',
			'google' => 'IBM+Plex+Sans:ital,wght@0,400;0,600;0,700;1,400',
		),
		'montserrat'       => array(
			'label'  => 'Montserrat',
			'stack'  => 'Montserrat, "Segoe UI", Arial, sans-serif',
			'google' => 'Montserrat:wght@400;500;600;700;800',
		),
		'oswald'           => array(
			'label'  => 'Oswald',
			'stack'  => 'Oswald, "Arial Narrow", Arial, sans-serif',
			'google' => 'Oswald:wght@400;500;600;700',
		),
		'barlow-condensed' => array(
			'label'  => 'Barlow Condensed',
			'stack'  => '"Barlow Condensed", "Arial Narrow", Arial, sans-serif',
			'google' => 'Barlow+Condensed:wght@400;500;600;700',
		),
		'roboto-condensed' => array(
			'label'  => 'Roboto Condensed',
			'stack'  => '"Roboto Condensed", "Arial Narrow", Arial, sans-serif',
			'google' => 'Roboto+Condensed:wght@400;500;600;700',
		),
		'exo-2'            => array(
			'label'  => 'Exo 2',
			'stack'  => '"Exo 2", "Segoe UI", Arial, sans-serif',
			'google' => 'Exo+2:wght@400;500;600;700',
		),
		'roboto-slab'      => array(
			'label'  => 'Roboto Slab',
			'stack'  => '"Roboto Slab", Georgia, serif',
			'google' => 'Roboto+Slab:wght@400;500;600;700',
		),
		'merriweather'     => array(
			'label'  => 'Merriweather',
			'stack'  => 'Merriweather, Georgia, serif',
			'google' => 'Merriweather:ital,wght@0,400;0,700;1,400',
		),
	);

	/**
	 * Filters the font choices.
	 *
	 * @param array $fonts Font slug => array( label, stack, google? ).
	 */
	return apply_filters( 'crrg_fonts', $fonts );
}

/**
 * Font choices formatted for a Customizer select.
 *
 * @return array
 */
function crrg_font_choices() {
	return wp_list_pluck( crrg_fonts(), 'label' );
}

/**
 * CSS font-family stack for a font slug.
 *
 * @param string $slug Font slug.
 * @return string
 */
function crrg_font_stack( $slug ) {
	$fonts = crrg_fonts();

	return isset( $fonts[ $slug ] ) ? $fonts[ $slug ]['stack'] : $fonts['system']['stack'];
}

/**
 * Google Fonts stylesheet URL for the selected fonts, or '' when none is needed.
 *
 * @return string
 */
function crrg_google_fonts_url() {
	if ( 'google' !== crrg_get_option( 'font_source' ) ) {
		return '';
	}

	$fonts    = crrg_fonts();
	$families = array();

	foreach ( array( crrg_get_option( 'body_font' ), crrg_get_option( 'heading_font' ) ) as $slug ) {
		if ( ! empty( $fonts[ $slug ]['google'] ) ) {
			$families[ $slug ] = 'family=' . $fonts[ $slug ]['google'];
		}
	}

	if ( empty( $families ) ) {
		return '';
	}

	return 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap';
}

/**
 * Preconnect to the Google Fonts hosts when they are in use.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array
 */
function crrg_font_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && crrg_google_fonts_url() ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'crrg_font_resource_hints', 10, 2 );
