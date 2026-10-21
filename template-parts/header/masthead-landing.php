<?php
/**
 * Header for the Landing Page template: logo, optional landing menu and button.
 *
 * @package CRRG
 */

?>
<header id="masthead" class="site-header site-header--default site-header--landing">
	<div class="container site-header__inner">
		<?php crrg_site_branding(); ?>
		<?php if ( has_nav_menu( 'landing' ) ) : ?>
			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Landing page', 'crrg' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'landing',
						'menu_id'        => 'landing-menu',
						'menu_class'     => 'menu primary-menu',
						'container'      => false,
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>
		<div class="header-actions">
			<?php crrg_header_cta(); ?>
			<?php
			if ( has_nav_menu( 'landing' ) ) {
				crrg_menu_toggle();
			}
			?>
		</div>
	</div>
</header><!-- #masthead -->
