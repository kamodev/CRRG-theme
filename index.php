<?php
/**
 * Main template: blog index, archives and search results.
 *
 * @package CRRG
 */

get_header();

crrg_layout_open();

get_template_part( 'template-parts/archive-header' );

if ( have_posts() ) {
	get_template_part( 'template-parts/post-list' );
	crrg_pagination();
} else {
	get_template_part( 'template-parts/content', 'none' );
}

crrg_layout_close();

get_footer();
