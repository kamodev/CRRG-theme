<?php
/**
 * Inline SVG icon set.
 *
 * Outline icons use a 24x24 grid with currentColor strokes; brand icons are
 * filled. Icons inherit the text colour of their parent.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Raw SVG inner markup keyed by icon name.
 *
 * @return array
 */
function crrg_icon_library() {
	$icons = array(
		// Interface.
		'search'       => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/>',
		'close'        => '<path d="M18 6 6 18M6 6l12 12"/>',
		'menu'         => '<path d="M3 6h18M3 12h18M3 18h18"/>',
		'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		'arrow-up'     => '<path d="M12 19V5M5 12l7-7 7 7"/>',
		'arrow-right'  => '<path d="M5 12h14M12 5l7 7-7 7"/>',
		'arrow-left'   => '<path d="M19 12H5M12 19l-7-7 7-7"/>',
		'calendar'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'user'         => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'folder'       => '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/>',
		'comment'      => '<path d="M21 12a8 8 0 0 1-11.6 7.1L3 21l1.9-6.4A8 8 0 1 1 21 12Z"/>',
		'tag'          => '<path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
		'map-pin'      => '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
		'phone'        => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2Z"/>',
		'mail'         => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'globe'        => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>',
		'external'     => '<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',

		// Feature / club icons.
		'radio'        => '<rect x="6" y="7" width="12" height="15" rx="2"/><path d="M9 7V2M9 11h6M9 14h6"/><circle cx="12" cy="18" r="1"/>',
		'tower'        => '<circle cx="12" cy="7" r="1.5"/><path d="M12 9v1M8.5 22 12 10l3.5 12M9.8 16.5h4.4M8.5 3.5a5 5 0 0 0 0 7M15.5 3.5a5 5 0 0 1 0 7M5.6 1.5a9 9 0 0 0 0 11M18.4 1.5a9 9 0 0 1 0 11"/>',
		'antenna'      => '<path d="M12 4v18M4 7h16M6 11h12M8 15h8"/>',
		'wave'         => '<path d="M2 12h4l2-7 4 14 3-10 2 3h5"/>',
		'digital'      => '<rect x="7" y="7" width="10" height="10" rx="1"/><path d="M10 2v3M14 2v3M10 19v3M14 19v3M2 10h3M2 14h3M19 10h3M19 14h3"/>',
		'users'        => '<circle cx="9" cy="8" r="4"/><path d="M1 21a8 8 0 0 1 16 0M16 3.1a4 4 0 0 1 0 7.8M23 21a8 8 0 0 0-5-7.4"/>',
		'shield'       => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
		'bolt'         => '<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8Z"/>',
		'first-aid'    => '<rect x="3" y="6" width="18" height="14" rx="2"/><path d="M9 6V4h6v2M12 10v6M9 13h6"/>',
		'book'         => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/>',
		'learn'        => '<path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>',
		'wrench'       => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9l-3.8 3.8Z"/>',
		'megaphone'    => '<path d="M3 11v2a1 1 0 0 0 1 1h3l5 4V6L7 10H4a1 1 0 0 0-1 1Z"/><path d="M16 8a5 5 0 0 1 0 8M19 5a9 9 0 0 1 0 14"/>',
		'heart'        => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>',
		'star'         => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1L12 2Z"/>',
		'mountain'     => '<path d="m2 20 7-12 4 6 3-4 6 10H2Z"/>',

		// Brands (filled).
		'facebook'     => '<path fill="currentColor" stroke="none" d="M14 8h3V4h-3c-2.8 0-4 1.8-4 4.3V10H7v4h3v8h4v-8h3l1-4h-4V8.6c0-.4.3-.6.6-.6Z"/>',
		'x'            => '<path fill="currentColor" stroke="none" d="M17.8 3h3.1l-6.8 7.8L22 21h-6.3l-4.9-6.4L5.2 21H2.1l7.3-8.3L1.8 3h6.4l4.4 5.9L17.8 3Zm-1.1 16.2h1.7L7.4 4.7H5.6l11.1 14.5Z"/>',
		'youtube'      => '<path fill="currentColor" stroke="none" d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12 31 31 0 0 0 1 16.8a3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8ZM9.8 15.1V8.9l5.8 3.1-5.8 3.1Z"/>',
		'instagram'    => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>',
		'discord'      => '<path fill="currentColor" stroke="none" d="M19.6 5.3A17 17 0 0 0 15.4 4l-.5 1a15.7 15.7 0 0 0-5.8 0l-.5-1a17 17 0 0 0-4.2 1.3C1.7 9.3 1 13.2 1.3 17a17 17 0 0 0 5.2 2.6l1.1-1.8a11 11 0 0 1-1.7-.8l.4-.3a12.2 12.2 0 0 0 11.4 0l.4.3c-.5.3-1.1.6-1.7.8l1.1 1.8a17 17 0 0 0 5.2-2.6c.4-4.4-.7-8.3-3.1-11.7ZM8.7 14.7c-1 0-1.9-1-1.9-2.1s.8-2.1 1.9-2.1 1.9 1 1.9 2.1-.8 2.1-1.9 2.1Zm6.6 0c-1 0-1.9-1-1.9-2.1s.8-2.1 1.9-2.1 1.9 1 1.9 2.1-.8 2.1-1.9 2.1Z"/>',
		'github'       => '<path fill="currentColor" stroke="none" d="M12 .5a11.5 11.5 0 0 0-3.6 22.4c.6.1.8-.3.8-.6v-2c-3.2.7-3.9-1.5-3.9-1.5-.5-1.3-1.3-1.7-1.3-1.7-1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1 1.8 2.8 1.3 3.5 1 .1-.8.4-1.3.7-1.6-2.6-.3-5.3-1.3-5.3-5.7 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.1 0 0 1-.3 3.2 1.2a11 11 0 0 1 5.8 0c2.2-1.5 3.2-1.2 3.2-1.2.6 1.6.2 2.8.1 3.1.8.8 1.2 1.9 1.2 3.1 0 4.4-2.7 5.4-5.3 5.7.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6A11.5 11.5 0 0 0 12 .5Z"/>',
		'linkedin'     => '<path fill="currentColor" stroke="none" d="M5 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9.5h4V21H3V9.5Zm6.5 0h3.8v1.6h.1c.5-1 1.8-2 3.8-2 4 0 4.8 2.6 4.8 6V21h-4v-5.1c0-1.2 0-2.8-1.7-2.8s-2 1.3-2 2.7V21h-4V9.5Z"/>',
		'mastodon'     => '<path fill="currentColor" stroke="none" d="M21.3 8.2c0-4.3-2.8-5.6-2.8-5.6C17.1 2 14.6 1.7 12 1.7h-.1c-2.6 0-5.1.3-6.5.9 0 0-2.8 1.3-2.8 5.6 0 1 0 2.2.01 3.4.1 4.3.8 8.5 4.7 9.5 1.8.5 3.4.6 4.6.5 2.3-.1 3.5-.8 3.5-.8l-.1-1.6s-1.6.5-3.4.5c-1.8-.1-3.7-.2-4-2.4a4.6 4.6 0 0 1 0-.7s1.8.4 4 .5c1.4.1 2.7-.1 4-.2 2.5-.3 4.7-1.9 5-3.3.4-2.2.4-5.4.4-5.4Zm-3.4 5.6h-2.1V8.7c0-1.1-.5-1.6-1.4-1.6-1 0-1.5.6-1.5 1.9v2.8h-2.1V9c0-1.3-.5-1.9-1.5-1.9-.9 0-1.4.5-1.4 1.6v5.1H5.8V8.5c0-1.1.3-1.9.8-2.6.6-.6 1.3-1 2.2-1 1.1 0 1.9.4 2.4 1.3l.5.9.5-.9c.5-.9 1.4-1.3 2.4-1.3.9 0 1.6.3 2.2 1 .5.6.8 1.5.8 2.6v5.3Z"/>',
		'reddit'       => '<path fill="currentColor" stroke="none" d="M22 12.1a2.2 2.2 0 0 0-3.7-1.6 10.8 10.8 0 0 0-5.8-1.8l1-4.6 3.2.7a1.6 1.6 0 1 0 .2-1l-3.6-.8a.5.5 0 0 0-.6.4l-1.1 5.3a10.8 10.8 0 0 0-5.9 1.8 2.2 2.2 0 1 0-2.4 3.6 4.3 4.3 0 0 0 0 .7c0 3.6 4.2 6.5 9.3 6.5s9.3-2.9 9.3-6.5a4.3 4.3 0 0 0 0-.7 2.2 2.2 0 0 0 1.1-2Zm-15.8 1.6a1.6 1.6 0 1 1 1.6 1.6 1.6 1.6 0 0 1-1.6-1.6Zm9 4.3a5.9 5.9 0 0 1-4.2 1.3 5.9 5.9 0 0 1-4.2-1.3.4.4 0 0 1 .6-.6 5.1 5.1 0 0 0 3.6 1 5.1 5.1 0 0 0 3.6-1 .4.4 0 1 1 .6.6Zm-.3-2.7a1.6 1.6 0 1 1 1.6-1.6 1.6 1.6 0 0 1-1.6 1.6Z"/>',
		'groupsio'     => '<rect x="2" y="7" width="15" height="12" rx="2"/><path d="m2 9 7.5 5L17 9M6 4h14a2 2 0 0 1 2 2v9"/>',
		'email'        => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'rss'          => '<path d="M4 11a9 9 0 0 1 9 9M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1.5" fill="currentColor" stroke="none"/>',
	);

	/**
	 * Filters the icon library. Values are inner SVG markup on a 24x24 grid.
	 *
	 * @param array $icons Icon name => SVG inner markup.
	 */
	return apply_filters( 'crrg_icon_library', $icons );
}

/**
 * Return an inline SVG icon.
 *
 * @param string $name  Icon name.
 * @param array  $args  Optional. 'size' (int px), 'class' (string), 'title' (accessible label).
 * @return string
 */
function crrg_icon( $name, $args = array() ) {
	$icons = crrg_icon_library();

	if ( empty( $icons[ $name ] ) ) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		array(
			'size'  => 24,
			'class' => '',
			'title' => '',
		)
	);

	$classes = trim( 'crrg-icon crrg-icon--' . sanitize_html_class( $name ) . ' ' . $args['class'] );
	$a11y    = $args['title']
		? ' role="img" aria-label="' . esc_attr( $args['title'] ) . '"'
		: ' aria-hidden="true" focusable="false"';

	return sprintf(
		'<svg class="%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"%3$s>%4$s</svg>',
		esc_attr( $classes ),
		absint( $args['size'] ),
		$a11y,
		$icons[ $name ] // Trusted, theme-defined markup.
	);
}

/**
 * Icons offered for front page cards and the info bar.
 *
 * @return array
 */
function crrg_feature_icon_choices() {
	return array(
		''          => __( 'None', 'crrg' ),
		'radio'     => __( 'Handheld radio', 'crrg' ),
		'tower'     => __( 'Radio tower', 'crrg' ),
		'antenna'   => __( 'Antenna', 'crrg' ),
		'wave'      => __( 'Signal wave', 'crrg' ),
		'digital'   => __( 'Digital / chip', 'crrg' ),
		'users'     => __( 'People', 'crrg' ),
		'shield'    => __( 'Shield', 'crrg' ),
		'first-aid' => __( 'First aid', 'crrg' ),
		'bolt'      => __( 'Power / bolt', 'crrg' ),
		'learn'     => __( 'Learning', 'crrg' ),
		'book'      => __( 'Book', 'crrg' ),
		'wrench'    => __( 'Wrench', 'crrg' ),
		'megaphone' => __( 'Megaphone', 'crrg' ),
		'calendar'  => __( 'Calendar', 'crrg' ),
		'clock'     => __( 'Clock', 'crrg' ),
		'map-pin'   => __( 'Map pin', 'crrg' ),
		'mountain'  => __( 'Mountain', 'crrg' ),
		'globe'     => __( 'Globe', 'crrg' ),
		'mail'      => __( 'Mail', 'crrg' ),
		'phone'     => __( 'Phone', 'crrg' ),
		'heart'     => __( 'Heart', 'crrg' ),
		'star'      => __( 'Star', 'crrg' ),
	);
}

/**
 * Social networks supported by the Social Links options.
 *
 * @return array Network key => label.
 */
function crrg_social_networks() {
	return array(
		'facebook'  => 'Facebook',
		'x'         => 'X (Twitter)',
		'youtube'   => 'YouTube',
		'instagram' => 'Instagram',
		'discord'   => 'Discord',
		'github'    => 'GitHub',
		'linkedin'  => 'LinkedIn',
		'mastodon'  => 'Mastodon',
		'reddit'    => 'Reddit',
		'groupsio'  => 'Groups.io',
		'email'     => __( 'Email', 'crrg' ),
	);
}
