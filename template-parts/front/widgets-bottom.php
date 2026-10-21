<?php
/**
 * Front Page Bottom widget area.
 *
 * @package CRRG
 */

if ( ! is_active_sidebar( 'front-page-bottom' ) ) {
	return;
}
?>
<section class="front-section front-widgets front-widgets--bottom">
	<div class="container front-widgets__grid">
		<?php dynamic_sidebar( 'front-page-bottom' ); ?>
	</div>
</section>
