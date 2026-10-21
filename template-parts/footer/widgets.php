<?php
/**
 * Footer widget columns.
 *
 * @package CRRG
 */

$crrg_columns = absint( crrg_get_option( 'footer_columns' ) );
$crrg_active  = array();

for ( $crrg_i = 1; $crrg_i <= $crrg_columns; $crrg_i++ ) {
	if ( is_active_sidebar( 'footer-' . $crrg_i ) ) {
		$crrg_active[] = 'footer-' . $crrg_i;
	}
}

if ( ! $crrg_active ) {
	return;
}

$crrg_classes = sprintf(
	'footer-widgets__grid footer-widgets__grid--%1$d footer-widgets__grid--%2$s',
	count( $crrg_active ),
	crrg_get_option( 'footer_columns_layout' )
);
?>
<div class="footer-widgets">
	<div class="container <?php echo esc_attr( $crrg_classes ); ?>">
		<?php foreach ( $crrg_active as $crrg_sidebar ) : ?>
			<div class="footer-widgets__column footer-widgets__column--<?php echo esc_attr( $crrg_sidebar ); ?>">
				<?php dynamic_sidebar( $crrg_sidebar ); ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
