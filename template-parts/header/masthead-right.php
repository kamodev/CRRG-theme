<?php
/**
 * Header layout: menu left, logo right.
 *
 * The markup keeps the logo first for screen readers and keyboard users;
 * CSS moves it to the right.
 *
 * @package CRRG
 */

?>
<header id="masthead" class="site-header site-header--right">
	<div class="container site-header__inner">
		<?php crrg_site_branding(); ?>
		<?php crrg_primary_menu(); ?>
		<?php get_template_part( 'template-parts/header/actions' ); ?>
	</div>
</header><!-- #masthead -->
