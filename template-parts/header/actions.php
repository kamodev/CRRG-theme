<?php
/**
 * Header action buttons: social icons, search, call to action, mobile menu.
 *
 * Optional $args: 'search' (bool), 'social' (bool).
 *
 * @package CRRG
 */

$crrg_args = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'search' => true,
		'social' => true,
	)
);
?>
<div class="header-actions">
	<?php
	if ( $crrg_args['social'] && crrg_get_option( 'header_social' ) ) {
		crrg_social_links( 'header' );
	}
	if ( $crrg_args['search'] ) {
		crrg_search_toggle();
	}
	crrg_header_cta();
	crrg_menu_toggle();
	?>
</div>
