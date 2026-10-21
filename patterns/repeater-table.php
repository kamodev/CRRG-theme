<?php
/**
 * Title: Repeater table
 * Slug: crrg/repeater-table
 * Categories: crrg, text
 * Keywords: repeater, frequency, offset, tone, ham
 * Description: A table of repeaters with output, input, tone and notes.
 *
 * @package CRRG
 */

?>
<!-- wp:heading {"className":"is-style-crrg-underline"} -->
<h2 class="wp-block-heading is-style-crrg-underline"><?php esc_html_e( 'Our Repeaters', 'crrg' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'All machines are open to licensed amateurs. Please identify, pause between transmissions, and give priority to emergency traffic.', 'crrg' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:table {"className":"is-style-crrg-frequencies"} -->
<figure class="wp-block-table is-style-crrg-frequencies"><table><thead><tr><th><?php esc_html_e( 'Site', 'crrg' ); ?></th><th><?php esc_html_e( 'Output', 'crrg' ); ?></th><th><?php esc_html_e( 'Input', 'crrg' ); ?></th><th><?php esc_html_e( 'Tone', 'crrg' ); ?></th><th><?php esc_html_e( 'Notes', 'crrg' ); ?></th></tr></thead><tbody><tr><td><?php esc_html_e( 'Mestaa’ėhehe Mountain (2 m)', 'crrg' ); ?></td><td>146.670</td><td>146.070</td><td>100.0 Hz</td><td><?php esc_html_e( 'Primary repeater, wide-area coverage. Home of the Sunday net.', 'crrg' ); ?></td></tr><tr><td><?php esc_html_e( 'Smokey Hill / Denver (2 m)', 'crrg' ); ?></td><td>145.400</td><td>144.800</td><td>100.0 Hz</td><td><?php esc_html_e( 'Linked to the 70 cm machine.', 'crrg' ); ?></td></tr><tr><td><?php esc_html_e( 'Smokey Hill / Denver (70 cm)', 'crrg' ); ?></td><td>448.500</td><td>443.500</td><td>100.0 Hz</td><td><?php esc_html_e( 'Linked to 145.400.', 'crrg' ); ?></td></tr></tbody></table><figcaption class="wp-element-caption"><?php esc_html_e( 'Frequencies in MHz. Tone is CTCSS (PL) on the input.', 'crrg' ); ?></figcaption></figure>
<!-- /wp:table -->
