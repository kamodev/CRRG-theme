<?php
/**
 * Author bio box.
 *
 * @package CRRG
 */

$crrg_author_id = is_author() ? get_queried_object_id() : (int) get_the_author_meta( 'ID' );
$crrg_bio       = get_the_author_meta( 'description', $crrg_author_id );

if ( ! $crrg_author_id || ( ! $crrg_bio && ! is_author() ) ) {
	return;
}
?>
<section class="author-box">
	<div class="author-box__avatar"><?php echo get_avatar( $crrg_author_id, 96 ); ?></div>
	<div class="author-box__body">
		<h2 class="author-box__name">
			<?php
			printf(
				/* translators: %s: author name. */
				esc_html__( 'About %s', 'crrg' ),
				esc_html( get_the_author_meta( 'display_name', $crrg_author_id ) )
			);
			?>
		</h2>
		<?php if ( $crrg_bio ) : ?>
			<p class="author-box__bio"><?php echo wp_kses_post( $crrg_bio ); ?></p>
		<?php endif; ?>
		<?php if ( ! is_author() ) : ?>
			<a class="author-box__link" href="<?php echo esc_url( get_author_posts_url( $crrg_author_id ) ); ?>">
				<?php esc_html_e( 'View all posts', 'crrg' ); ?>
				<?php echo crrg_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		<?php endif; ?>
	</div>
</section>
