<?php
/**
 * Front Page Top widget area.
 *
 * @package CRRG
 */

if ( ! is_active_sidebar( 'front-page-top' ) ) {
	return;
}
?>
<section class="front-section front-widgets front-widgets--top">
	<div class="container front-widgets__grid">
		<?php dynamic_sidebar( 'front-page-top' ); ?>
	</div>
</section>
