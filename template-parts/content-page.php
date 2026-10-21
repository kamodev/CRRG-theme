<?php
/**
 * Page content.
 *
 * @package CRRG
 */

// The featured image is the banner background when that option is on.
$crrg_thumb_in_banner = crrg_show_page_banner() && crrg_get_option( 'page_header_featured' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' ); ?>>
	<?php if ( crrg_show_entry_title() ) : ?>
		<header class="entry-header">
			<?php crrg_breadcrumbs(); ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>
	<?php endif; ?>

	<?php
	if ( ! $crrg_thumb_in_banner && ! crrg_is_landing() ) {
		crrg_post_thumbnail( 'crrg-wide' );
	}
	?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'crrg' ) . '"><span class="page-links__label">' . esc_html__( 'Pages:', 'crrg' ) . '</span>',
				'after'  => '</nav>',
			)
		);
		?>
	</div>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php crrg_entry_footer(); ?>
		</footer>
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
