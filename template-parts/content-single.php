<?php
/**
 * Single post content.
 *
 * @package CRRG
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single' ); ?>>
	<?php if ( crrg_show_entry_title() ) : ?>
		<header class="entry-header">
			<?php crrg_breadcrumbs(); ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php
			if ( crrg_get_option( 'single_meta' ) ) {
				crrg_entry_meta();
			}
			?>
		</header>
	<?php endif; ?>

	<?php
	if ( crrg_get_option( 'single_thumbnail' ) ) {
		crrg_post_thumbnail( crrg_has_sidebar() ? 'crrg-wide' : 'full' );
	}
	?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				/* translators: %s: post title. */
				esc_html__( 'Continue reading %s', 'crrg' ),
				'<span class="screen-reader-text">' . get_the_title() . '</span>'
			)
		);

		wp_link_pages(
			array(
				'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'crrg' ) . '"><span class="page-links__label">' . esc_html__( 'Pages:', 'crrg' ) . '</span>',
				'after'  => '</nav>',
			)
		);
		?>
	</div>

	<footer class="entry-footer">
		<?php crrg_entry_footer(); ?>
	</footer>
</article><!-- #post-<?php the_ID(); ?> -->
