<?php
/**
 * Front page content: the static page's content, or the latest posts when
 * the front page shows posts.
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'front_content_enable' ) ) {
	return;
}

if ( 'posts' === get_option( 'show_on_front' ) ) :
	if ( ! have_posts() ) {
		return;
	}
	?>
	<section class="front-section front-posts-loop">
		<?php crrg_layout_open( 'front-page__posts', 'div' ); ?>
			<h2 class="section-header__title"><?php echo esc_html( crrg_get_option( 'front_posts_title' ) ); ?></h2>
			<?php get_template_part( 'template-parts/post-list' ); ?>
			<?php crrg_pagination(); ?>
		<?php crrg_layout_close( 'div' ); ?>
	</section>
	<?php
	return;
endif;

while ( have_posts() ) :
	the_post();

	if ( '' === trim( get_the_content() ) ) {
		continue;
	}
	?>
	<section class="front-section front-content">
		<div class="container">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--front' ); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	</section>
	<?php
endwhile;
