<?php
/**
 * Front page feature cards.
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'features_enable' ) ) {
	return;
}

$crrg_cards = array();
for ( $crrg_n = 1; $crrg_n <= 4; $crrg_n++ ) {
	$crrg_card_title = crrg_get_option( "feature_{$crrg_n}_title" );
	if ( $crrg_card_title ) {
		$crrg_cards[] = array(
			'icon'  => crrg_get_option( "feature_{$crrg_n}_icon" ),
			'title' => $crrg_card_title,
			'text'  => crrg_get_option( "feature_{$crrg_n}_text" ),
			'url'   => crrg_get_option( "feature_{$crrg_n}_url" ),
		);
	}
}

if ( ! $crrg_cards ) {
	return;
}

$crrg_heading   = crrg_get_option( 'features_title' );
$crrg_intro     = crrg_get_option( 'features_subtitle' );
$crrg_link_text = crrg_get_option( 'features_link_text' );
$crrg_columns   = min( count( $crrg_cards ), absint( crrg_get_option( 'features_columns' ) ) );
?>
<section class="front-section crrg-features">
	<div class="container">
		<?php if ( $crrg_heading || $crrg_intro ) : ?>
			<header class="section-header">
				<?php if ( $crrg_heading ) : ?>
					<h2 class="section-header__title"><?php echo esc_html( $crrg_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $crrg_intro ) : ?>
					<p class="section-header__intro"><?php echo wp_kses( $crrg_intro, crrg_allowed_inline_html() ); ?></p>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="crrg-features__grid crrg-features__grid--<?php echo absint( max( 1, $crrg_columns ) ); ?>">
			<?php foreach ( $crrg_cards as $crrg_card ) : ?>
				<article class="feature-card">
					<?php if ( $crrg_card['icon'] ) : ?>
						<span class="feature-card__icon"><?php echo crrg_icon( $crrg_card['icon'], array( 'size' => 32 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
					<h3 class="feature-card__title">
						<?php if ( $crrg_card['url'] ) : ?>
							<a href="<?php echo esc_url( crrg_resolve_url( $crrg_card['url'] ) ); ?>"><?php echo esc_html( $crrg_card['title'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $crrg_card['title'] ); ?>
						<?php endif; ?>
					</h3>
					<?php if ( $crrg_card['text'] ) : ?>
						<p class="feature-card__text"><?php echo wp_kses( $crrg_card['text'], crrg_allowed_inline_html() ); ?></p>
					<?php endif; ?>
					<?php if ( $crrg_card['url'] && $crrg_link_text ) : ?>
						<span class="feature-card__more" aria-hidden="true"><?php echo esc_html( $crrg_link_text ); ?><?php echo crrg_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
