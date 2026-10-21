<?php
/**
 * Shown when a list has no posts.
 *
 * @package CRRG
 */

?>
<section class="no-results not-found entry-card">
	<div class="entry-card__body">
		<h2 class="entry-title"><?php esc_html_e( 'Nothing here yet', 'crrg' ); ?></h2>

		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						/* translators: %s: link to the new post screen. */
						__( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'crrg' ),
						array( 'a' => array( 'href' => array() ) )
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Nothing matched your search. Please try different keywords.', 'crrg' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'We could not find what you were looking for. Searching may help.', 'crrg' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
