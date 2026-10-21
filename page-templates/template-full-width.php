<?php
/**
 * Template Name: Full Width
 * Template Post Type: page, post
 *
 * Edge-to-edge content area with no sidebars, for wide and full-width blocks.
 * The layout is applied by crrg_get_requested_layout(); the markup is the
 * regular page or post template.
 *
 * @package CRRG
 */

get_template_part( is_page() ? 'page' : 'single' );
