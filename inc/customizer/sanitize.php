<?php
/**
 * Customizer sanitisation callbacks.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitise a checkbox / toggle.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function crrg_sanitize_checkbox( $value ) {
	return ( isset( $value ) && ( true === $value || '1' === $value || 1 === $value || 'true' === $value ) );
}

/**
 * Sanitise a select / radio value against the control's choices.
 *
 * @param string               $value   Raw value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function crrg_sanitize_choice( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	$choices = ( $control && isset( $control->choices ) ) ? (array) $control->choices : array();

	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return $setting->default;
}

/**
 * Sanitise an integer and clamp it to the control's min/max input attributes.
 *
 * @param mixed                $value   Raw value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int
 */
function crrg_sanitize_number( $value, $setting ) {
	$value   = (int) $value;
	$control = $setting->manager->get_control( $setting->id );
	$attrs   = ( $control && isset( $control->input_attrs ) ) ? $control->input_attrs : array();

	if ( isset( $attrs['min'] ) && $value < (int) $attrs['min'] ) {
		$value = (int) $attrs['min'];
	}
	if ( isset( $attrs['max'] ) && $value > (int) $attrs['max'] ) {
		$value = (int) $attrs['max'];
	}

	return $value;
}

/**
 * Sanitise a hex colour, falling back to the setting default.
 *
 * @param string               $value   Raw value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function crrg_sanitize_hex( $value, $setting ) {
	$color = sanitize_hex_color( $value );

	return $color ? $color : $setting->default;
}

/**
 * Sanitise short HTML (top bar text, footer copyright, hero copy).
 *
 * @param string $value Raw value.
 * @return string
 */
function crrg_sanitize_html( $value ) {
	return wp_kses( $value, crrg_allowed_inline_html() );
}

/**
 * Inline HTML allowed in text options.
 *
 * @return array
 */
function crrg_allowed_inline_html() {
	return array(
		'a'      => array(
			'href'   => true,
			'title'  => true,
			'target' => true,
			'rel'    => true,
			'class'  => true,
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
		'i'      => array( 'class' => true ),
		'span'   => array( 'class' => true ),
		'small'  => array(),
		'code'   => array(),
	);
}

/**
 * Sanitise a URL or site-relative path such as "/repeaters/".
 *
 * @param string $value Raw value.
 * @return string
 */
function crrg_sanitize_url( $value ) {
	// esc_url_raw() keeps values that start with "/", "#" or "?" relative.
	return esc_url_raw( trim( (string) $value ) );
}

/**
 * Sanitise the comma separated front page section order.
 *
 * Unknown sections are dropped and missing ones are appended so a section
 * can never disappear because of a bad value.
 *
 * @param string $value Raw value.
 * @return string
 */
function crrg_sanitize_sections_order( $value ) {
	$known = array_keys( crrg_front_sections() );
	$order = array_filter( array_map( 'sanitize_key', explode( ',', (string) $value ) ) );
	$order = array_values( array_unique( array_intersect( $order, $known ) ) );

	foreach ( $known as $section ) {
		if ( ! in_array( $section, $order, true ) ) {
			$order[] = $section;
		}
	}

	return implode( ',', $order );
}

/**
 * Sanitise an email address or mailto link for the social email field.
 *
 * @param string $value Raw value.
 * @return string
 */
function crrg_sanitize_email_link( $value ) {
	$value = trim( str_replace( 'mailto:', '', (string) $value ) );

	return is_email( $value ) ? $value : '';
}
