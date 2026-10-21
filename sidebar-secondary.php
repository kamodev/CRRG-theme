<?php
/**
 * Secondary (left) sidebar used by the dual sidebar layout.
 *
 * @package CRRG
 */

if ( ! is_active_sidebar( 'sidebar-secondary' ) ) {
	return;
}
?>
<aside id="tertiary" class="widget-area sidebar sidebar--secondary" aria-label="<?php esc_attr_e( 'Secondary sidebar', 'crrg' ); ?>">
	<div class="sidebar__inner">
		<?php dynamic_sidebar( 'sidebar-secondary' ); ?>
	</div>
</aside><!-- #tertiary -->
