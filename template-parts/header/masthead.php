<?php
/**
 * Header layout: logo left, menu right.
 *
 * @package CRRG
 */

?>
<header id="masthead" class="site-header site-header--default">
	<div class="container site-header__inner">
		<?php crrg_site_branding(); ?>
		<?php crrg_primary_menu(); ?>
		<?php get_template_part( 'template-parts/header/actions' ); ?>
	</div>
</header><!-- #masthead -->
