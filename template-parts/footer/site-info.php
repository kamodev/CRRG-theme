<?php
/**
 * Footer bottom bar: copyright, footer menu, social icons, credit.
 *
 * @package CRRG
 */

?>
<div class="site-info">
	<div class="container site-info__inner">
		<div class="site-info__start">
			<p class="site-info__copyright"><?php echo crrg_copyright_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised with wp_kses(). ?></p>
			<?php if ( crrg_get_option( 'footer_credit' ) ) : ?>
				<p class="site-info__credit">
					<?php
					printf(
						/* translators: %s: WordPress. */
						esc_html__( 'Powered by %s', 'crrg' ),
						'<a href="' . esc_url( __( 'https://wordpress.org/', 'crrg' ) ) . '">WordPress</a>'
					);
					?>
				</p>
			<?php endif; ?>
		</div>
		<div class="site-info__end">
			<?php
			if ( crrg_get_option( 'footer_menu' ) && ! crrg_is_landing() ) {
				crrg_simple_menu( 'footer', __( 'Footer', 'crrg' ) );
			}
			if ( crrg_get_option( 'footer_social' ) ) {
				crrg_social_links( 'footer' );
			}
			?>
		</div>
	</div>
</div>
