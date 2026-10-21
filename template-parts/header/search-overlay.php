<?php
/**
 * Full-width search panel opened by the header search button.
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'header_search' ) || crrg_is_landing() ) {
	return;
}
?>
<div id="search-overlay" class="search-overlay" hidden>
	<div class="container search-overlay__inner">
		<?php get_search_form( array( 'aria_label' => __( 'Site search', 'crrg' ) ) ); ?>
		<button type="button" class="header-icon-button search-overlay__close">
			<?php echo crrg_icon( 'close', array( 'size' => 24 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close search', 'crrg' ); ?></span>
		</button>
	</div>
</div>
