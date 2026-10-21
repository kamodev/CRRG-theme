<?php
/**
 * Search form.
 *
 * @package CRRG
 */

$crrg_search_id = wp_unique_id( 'search-field-' );
$crrg_label     = ! empty( $args['aria_label'] ) ? $args['aria_label'] : __( 'Search', 'crrg' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $crrg_label ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $crrg_search_id ); ?>"><?php esc_html_e( 'Search for:', 'crrg' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $crrg_search_id ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'crrg' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-submit">
		<?php echo crrg_icon( 'search', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'crrg' ); ?></span>
	</button>
</form>
