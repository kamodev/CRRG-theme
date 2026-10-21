<?php
/**
 * A post in a list (blog, archives).
 *
 * @package CRRG
 */

$crrg_style = crrg_get_option( 'blog_style' );
$crrg_sizes = array(
	'list'    => 'crrg-list',
	'classic' => 'crrg-wide',
	'grid'    => 'crrg-card',
);
$crrg_size  = isset( $crrg_sizes[ $crrg_style ] ) ? $crrg_sizes[ $crrg_style ] : 'crrg-card';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( crrg_get_option( 'archive_thumbnail' ) ) {
		crrg_post_thumbnail( $crrg_size );
	}
	?>
	<div class="entry-card__body">
		<header class="entry-header">
			<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
				<span class="entry-badge"><?php esc_html_e( 'Featured', 'crrg' ); ?></span>
			<?php endif; ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			<?php crrg_entry_meta( true ); ?>
		</header>

		<?php if ( crrg_get_option( 'excerpt_length' ) ) : ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>

		<footer class="entry-card__footer">
			<?php crrg_read_more(); ?>
		</footer>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
