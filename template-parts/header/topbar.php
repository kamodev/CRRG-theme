<?php
/**
 * Top bar above the header.
 *
 * @package CRRG
 */

if ( ! crrg_get_option( 'topbar_enable' ) ) {
	return;
}

$crrg_topbar_text   = crrg_get_option( 'topbar_text' );
$crrg_topbar_menu   = crrg_get_option( 'topbar_show_menu' ) && has_nav_menu( 'topbar' );
$crrg_topbar_social = crrg_get_option( 'topbar_show_social' ) && crrg_get_social_links();

if ( ! $crrg_topbar_text && ! $crrg_topbar_menu && ! $crrg_topbar_social && ! is_customize_preview() ) {
	return;
}
?>
<div class="topbar">
	<div class="container topbar__inner">
		<div class="topbar__text"><?php echo wp_kses( $crrg_topbar_text, crrg_allowed_inline_html() ); ?></div>
		<?php if ( $crrg_topbar_menu || $crrg_topbar_social ) : ?>
			<div class="topbar__end">
				<?php
				if ( $crrg_topbar_menu ) {
					crrg_simple_menu( 'topbar', __( 'Top bar', 'crrg' ) );
				}
				if ( $crrg_topbar_social ) {
					crrg_social_links( 'topbar' );
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
