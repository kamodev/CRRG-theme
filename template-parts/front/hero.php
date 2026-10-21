<?php
/**
 * Front page hero banner.
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'hero_enable' ) ) {
	return;
}

$crrg_classes = array(
	'crrg-hero',
	'crrg-hero--' . crrg_get_option( 'hero_height' ),
	'crrg-hero--align-' . crrg_get_option( 'hero_align' ),
	crrg_get_option( 'hero_image' ) ? 'crrg-hero--has-image' : 'crrg-hero--pattern',
);
$crrg_title   = crrg_get_option( 'hero_title' );
$crrg_text    = crrg_get_option( 'hero_text' );
?>
<section class="<?php echo esc_attr( implode( ' ', $crrg_classes ) ); ?>">
	<div class="crrg-hero__overlay" aria-hidden="true"></div>
	<div class="container crrg-hero__inner">
		<div class="crrg-hero__content">
			<?php if ( $crrg_title ) : ?>
				<h1 class="crrg-hero__title"><?php echo esc_html( $crrg_title ); ?></h1>
			<?php endif; ?>
			<?php if ( $crrg_text ) : ?>
				<p class="crrg-hero__text"><?php echo wp_kses( $crrg_text, crrg_allowed_inline_html() ); ?></p>
			<?php endif; ?>
			<div class="crrg-hero__buttons">
				<?php
				crrg_button( crrg_get_option( 'hero_btn1_text' ), crrg_get_option( 'hero_btn1_url' ), 'crrg-button--secondary crrg-button--large' );
				crrg_button( crrg_get_option( 'hero_btn2_text' ), crrg_get_option( 'hero_btn2_url' ), 'crrg-button--ghost crrg-button--large' );
				?>
			</div>
		</div>
	</div>
</section>
