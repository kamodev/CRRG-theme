<?php
/**
 * Front page latest posts (static front page only).
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'front_posts_enable' ) || 'posts' === get_option( 'show_on_front' ) ) {
	return;
}

$crrg_query = new WP_Query(
	array(
		'posts_per_page'      => absint( crrg_get_option( 'front_posts_count' ) ),
		'cat'                 => absint( crrg_get_option( 'front_posts_category' ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $crrg_query->have_posts() ) {
	return;
}

$crrg_title      = crrg_get_option( 'front_posts_title' );
$crrg_posts_page = (int) get_option( 'page_for_posts' );
$crrg_count      = (int) $crrg_query->post_count;
$crrg_columns    = ( 0 === $crrg_count % 4 ) ? 4 : 3;
?>
<section class="front-section front-latest">
	<div class="container">
		<header class="section-header section-header--split">
			<?php if ( $crrg_title ) : ?>
				<h2 class="section-header__title"><?php echo esc_html( $crrg_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $crrg_posts_page ) : ?>
				<a class="section-header__link" href="<?php echo esc_url( get_permalink( $crrg_posts_page ) ); ?>">
					<?php esc_html_e( 'View all', 'crrg' ); ?>
					<?php echo crrg_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endif; ?>
		</header>

		<div class="post-list post-list--grid post-list--cols-<?php echo absint( $crrg_columns ); ?>">
			<?php
			while ( $crrg_query->have_posts() ) :
				$crrg_query->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-card entry-card--grid' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'crrg-card', array( 'alt' => '' ) ); ?></a>
					<?php endif; ?>
					<div class="entry-card__body">
						<header class="entry-header">
							<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
							<?php crrg_entry_meta( true ); ?>
						</header>
						<div class="entry-summary"><?php the_excerpt(); ?></div>
						<footer class="entry-card__footer"><?php crrg_read_more(); ?></footer>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
