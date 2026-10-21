<?php
/**
 * Header layout: logo centred with the menu in a bar below.
 *
 * @package CRRG
 */

?>
<header id="masthead" class="site-header site-header--centered">
	<div class="container site-header__top">
		<div class="site-header__start">
			<?php
			if ( crrg_get_option( 'header_social' ) ) {
				crrg_social_links( 'header' );
			}
			?>
		</div>
		<?php crrg_site_branding(); ?>
		<?php get_template_part( 'template-parts/header/actions', null, array( 'social' => false ) ); ?>
	</div>
	<div class="site-header__nav">
		<div class="container">
			<?php crrg_primary_menu(); ?>
		</div>
	</div>
</header><!-- #masthead -->
