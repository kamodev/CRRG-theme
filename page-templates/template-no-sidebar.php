<?php
/**
 * Template Name: No Sidebar
 * Template Post Type: page, post
 *
 * Centred content without sidebars.
 * The layout is applied by crrg_get_requested_layout(); the markup is the
 * regular page or post template.
 *
 * @package CRRG
 */

get_template_part( is_page() ? 'page' : 'single' );
