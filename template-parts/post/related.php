<?php
/**
 * Related posts (same categories) under a single post.
 *
 * @package CRRG
 */

$crrg_categories = wp_get_post_categories( get_the_ID() );

if ( ! $crrg_categories ) {
	return;
}

$crrg_related = new WP_Query(
	array(
		'category__in'        => $crrg_categories,
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => absint( crrg_get_option( 'single_related_count' ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $crrg_related->have_posts() ) {
	return;
}
?>
<section class="related-posts">
	<h2 class="related-posts__title"><?php esc_html_e( 'Related Posts', 'crrg' ); ?></h2>
	<div class="related-posts__grid related-posts__grid--<?php echo absint( crrg_get_option( 'single_related_count' ) ); ?>">
		<?php
		while ( $crrg_related->have_posts() ) :
			$crrg_related->the_post();
			?>
			<article class="related-post">
				<?php if ( has_post_thumbnail() ) : ?>
					<a class="related-post__thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'crrg-card', array( 'alt' => '' ) ); ?></a>
				<?php endif; ?>
				<h3 class="related-post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<time class="related-post__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php
wp_reset_postdata();
