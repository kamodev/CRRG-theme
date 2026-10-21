<?php
/**
 * 404 page.
 *
 * @package CRRG
 */

get_header();

crrg_layout_open( 'error-404' );
?>
<section class="not-found-page">
	<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-defined SVG.
	echo crrg_icon(
		'wave',
		array(
			'size'  => 64,
			'class' => 'not-found-page__icon',
		)
	);
	?>
	<?php if ( ! crrg_show_page_banner() ) : ?>
		<h1 class="not-found-page__title"><?php esc_html_e( 'Page not found', 'crrg' ); ?></h1>
	<?php endif; ?>
	<p class="not-found-page__lead"><?php esc_html_e( 'No copy on that frequency. The page may have moved, or the link may be mistyped.', 'crrg' ); ?></p>
	<?php get_search_form(); ?>
	<p><?php crrg_button( __( 'Back to the home page', 'crrg' ), home_url( '/' ), 'crrg-button--primary' ); ?></p>
</section>
<?php
crrg_layout_close();

get_footer();
