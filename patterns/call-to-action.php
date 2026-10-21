<?php
/**
 * Title: Call to action band
 * Slug: crrg/call-to-action
 * Categories: crrg, call-to-action
 * Keywords: cta, join, donate, membership
 * Description: A full-width coloured band with a heading, text and button.
 *
 * @package CRRG
 */

?>
<!-- wp:group {"align":"full","className":"crrg-pattern-cta","backgroundColor":"crrg-secondary","textColor":"black","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull crrg-pattern-cta has-black-color has-crrg-secondary-background-color has-text-color has-background"><!-- wp:heading {"textAlign":"center","textColor":"black"} -->
<h2 class="wp-block-heading has-text-align-center has-black-color has-text-color"><?php esc_html_e( 'Help Keep the Repeaters on the Air', 'crrg' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'Every membership and donation goes straight to equipment, site costs and maintenance.', 'crrg' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"crrg-primary","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-crrg-primary-background-color has-text-color has-background wp-element-button" href="/membership/"><?php esc_html_e( 'Join or Renew', 'crrg' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
