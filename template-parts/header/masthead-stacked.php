<?php
/**
 * Header layout: logo and header widget area, menu bar below.
 *
 * @package CRRG
 */

?>
<header id="masthead" class="site-header site-header--stacked">
	<div class="container site-header__top">
		<?php crrg_site_branding(); ?>
		<?php if ( is_active_sidebar( 'header-widget' ) ) : ?>
			<div class="site-header__widgets">
				<?php dynamic_sidebar( 'header-widget' ); ?>
			</div>
		<?php endif; ?>
		<?php get_template_part( 'template-parts/header/actions', null, array( 'search' => false ) ); ?>
	</div>
	<div class="site-header__nav">
		<div class="container site-header__nav-inner">
			<?php crrg_primary_menu(); ?>
			<?php crrg_search_toggle(); ?>
		</div>
	</div>
</header><!-- #masthead -->
