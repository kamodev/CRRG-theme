<?php
/**
 * Pages. The layout page templates in /page-templates also use this file.
 *
 * @package CRRG
 */

get_header();

crrg_layout_open();

while ( have_posts() ) {
	the_post();

	get_template_part( 'template-parts/content', 'page' );

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

crrg_layout_close();

get_footer();
