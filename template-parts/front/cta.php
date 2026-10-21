<?php
/**
 * Front page call to action band.
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'cta_enable' ) ) {
	return;
}

$crrg_title = crrg_get_option( 'cta_title' );
$crrg_text  = crrg_get_option( 'cta_text' );

if ( ! $crrg_title && ! $crrg_text ) {
	return;
}
?>
<section class="crrg-cta">
	<div class="container crrg-cta__inner">
		<div class="crrg-cta__content">
			<?php if ( $crrg_title ) : ?>
				<h2 class="crrg-cta__title"><?php echo esc_html( $crrg_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $crrg_text ) : ?>
				<p class="crrg-cta__text"><?php echo wp_kses( $crrg_text, crrg_allowed_inline_html() ); ?></p>
			<?php endif; ?>
		</div>
		<?php crrg_button( crrg_get_option( 'cta_btn_text' ), crrg_get_option( 'cta_btn_url' ), 'crrg-button--light crrg-button--large' ); ?>
	</div>
</section>
