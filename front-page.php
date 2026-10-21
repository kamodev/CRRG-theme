<?php
/**
 * Front page: the sections from Customizer > Front Page Sections, in the
 * order chosen there.
 *
 * Works with a static front page and with "Your latest posts".
 *
 * @package CRRG
 */

get_header();
?>
<main id="primary" class="site-main front-page">
	<?php
	$crrg_order = explode( ',', crrg_sanitize_sections_order( crrg_get_option( 'front_sections_order' ) ) );

	// Later pages of "Your latest posts" only need the posts.
	if ( is_paged() ) {
		$crrg_order = array( 'content' );
	}

	foreach ( $crrg_order as $crrg_section ) {
		get_template_part( 'template-parts/front/' . str_replace( '_', '-', $crrg_section ) );
	}
	?>
</main><!-- #primary -->
<?php
get_footer();
