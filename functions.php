<?php
/**
 * CRRG theme bootstrap.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

define( 'CRRG_VERSION', '0.1.1' );
define( 'CRRG_DIR', get_template_directory() );
define( 'CRRG_URI', get_template_directory_uri() );

$crrg_includes = array(
	'inc/customizer/defaults.php',
	'inc/customizer/sanitize.php',
	'inc/customizer/customizer.php',
	'inc/customizer/css.php',
	'inc/setup.php',
	'inc/widget-areas.php',
	'inc/enqueue.php',
	'inc/fonts.php',
	'inc/icons.php',
	'inc/layout.php',
	'inc/template-tags.php',
	'inc/template-functions.php',
	'inc/navigation.php',
	'inc/breadcrumbs.php',
	'inc/meta-boxes.php',
	'inc/block-patterns.php',
	'inc/starter-content.php',
	'inc/widgets/class-crrg-widget.php',
	'inc/widgets/class-crrg-widget-repeaters.php',
	'inc/widgets/class-crrg-widget-net-schedule.php',
	'inc/widgets/class-crrg-widget-cta.php',
	'inc/widgets/class-crrg-widget-social.php',
	'inc/widgets/class-crrg-widget-recent-posts.php',
	'inc/widgets/class-crrg-widget-contact.php',
);

foreach ( $crrg_includes as $crrg_file ) {
	require_once CRRG_DIR . '/' . $crrg_file;
}
unset( $crrg_includes, $crrg_file );
