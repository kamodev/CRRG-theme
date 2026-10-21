<?php
/**
 * Front page info bar: up to four quick facts.
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'info_enable' ) ) {
	return;
}

$crrg_items = array();
for ( $crrg_n = 1; $crrg_n <= 4; $crrg_n++ ) {
	$crrg_item_title = crrg_get_option( "info_{$crrg_n}_title" );
	if ( $crrg_item_title ) {
		$crrg_items[] = array(
			'icon'  => crrg_get_option( "info_{$crrg_n}_icon" ),
			'title' => $crrg_item_title,
			'text'  => crrg_get_option( "info_{$crrg_n}_text" ),
		);
	}
}

if ( ! $crrg_items ) {
	return;
}
?>
<section class="crrg-info" aria-label="<?php esc_attr_e( 'Quick facts', 'crrg' ); ?>">
	<div class="container crrg-info__grid crrg-info__grid--<?php echo count( $crrg_items ); ?>">
		<?php foreach ( $crrg_items as $crrg_item ) : ?>
			<div class="crrg-info__item">
				<?php if ( $crrg_item['icon'] ) : ?>
					<span class="crrg-info__icon"><?php echo crrg_icon( $crrg_item['icon'], array( 'size' => 28 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
				<span class="crrg-info__body">
					<strong class="crrg-info__title"><?php echo esc_html( $crrg_item['title'] ); ?></strong>
					<?php if ( $crrg_item['text'] ) : ?>
						<span class="crrg-info__text"><?php echo wp_kses( $crrg_item['text'], crrg_allowed_inline_html() ); ?></span>
					<?php endif; ?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
