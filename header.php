<?php
/**
 * Site header.
 *
 * @package CRRG
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'crrg' ); ?></a>

	<?php
	if ( ! crrg_is_landing() ) {
		get_template_part( 'template-parts/header/topbar' );
	}

	get_template_part( 'template-parts/header/masthead', crrg_is_landing() ? 'landing' : crrg_get_option( 'header_layout' ) );

	get_template_part( 'template-parts/header/search-overlay' );
	crrg_mobile_menu();
	?>

	<div id="content" class="site-content">
		<?php crrg_page_banner(); ?>
