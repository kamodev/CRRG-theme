<?php
/**
 * Single post (and any custom post type without its own template).
 *
 * @package CRRG
 */

get_header();

crrg_layout_open();

while ( have_posts() ) {
	the_post();

	get_template_part( 'template-parts/content', 'single' );

	if ( 'post' === get_post_type() ) {
		if ( crrg_get_option( 'single_author_box' ) ) {
			get_template_part( 'template-parts/post/author-box' );
		}

		if ( crrg_get_option( 'single_post_nav' ) ) {
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . crrg_icon( 'arrow-left', array( 'size' => 14 ) ) . esc_html__( 'Previous', 'crrg' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'crrg' ) . crrg_icon( 'arrow-right', array( 'size' => 14 ) ) . '</span> <span class="nav-title">%title</span>',
				)
			);
		}

		if ( crrg_get_option( 'single_related' ) ) {
			get_template_part( 'template-parts/post/related' );
		}
	}

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

crrg_layout_close();

get_footer();
