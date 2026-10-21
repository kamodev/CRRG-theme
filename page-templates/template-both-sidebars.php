<?php
/**
 * Template Name: Both Sidebars
 * Template Post Type: page, post
 *
 * Content between the Secondary Sidebar (left) and the main sidebar (right).
 * The layout is applied by crrg_get_requested_layout(); the markup is the
 * regular page or post template.
 *
 * @package CRRG
 */

get_template_part( is_page() ? 'page' : 'single' );
