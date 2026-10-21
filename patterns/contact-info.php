<?php
/**
 * Title: Contact and meeting info
 * Slug: crrg/contact-info
 * Categories: crrg, columns
 * Keywords: contact, email, meeting, address
 * Description: Two columns with contact details and meeting information.
 *
 * @package CRRG
 */

?>
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"className":"is-style-crrg-underline"} -->
<h2 class="wp-block-heading is-style-crrg-underline"><?php esc_html_e( 'Contact Us', 'crrg' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Questions about the repeaters, nets or membership? Send us a note and a club officer will get back to you.', 'crrg' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong><?php esc_html_e( 'Email:', 'crrg' ); ?></strong> <?php esc_html_e( 'you@example.com', 'crrg' ); ?><br><strong><?php esc_html_e( 'On the air:', 'crrg' ); ?></strong> <?php esc_html_e( '146.670 MHz, PL 100.0 Hz', 'crrg' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-crrg-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-crrg-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Meetings', 'crrg' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Add your meeting day, time and location here.', 'crrg' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
