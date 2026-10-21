<?php
/**
 * Main sidebar.
 *
 * Which widget area is shown depends on the view (see crrg_get_main_sidebar()).
 *
 * @package CRRG
 */

$crrg_sidebar = crrg_get_main_sidebar();

if ( ! is_active_sidebar( $crrg_sidebar ) ) {
	return;
}
?>
<aside id="secondary" class="widget-area sidebar sidebar--primary" aria-label="<?php esc_attr_e( 'Sidebar', 'crrg' ); ?>">
	<div class="sidebar__inner">
		<?php dynamic_sidebar( $crrg_sidebar ); ?>
	</div>
</aside><!-- #secondary -->
