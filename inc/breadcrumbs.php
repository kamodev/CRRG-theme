<?php
/**
 * Breadcrumbs.
 *
 * Uses the breadcrumbs of an active SEO plugin when available so the trail
 * matches its structured data; otherwise builds a simple trail.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print breadcrumbs for the current view.
 */
function crrg_breadcrumbs() {
	if ( ! crrg_get_option( 'breadcrumbs_enable' ) || is_front_page() ) {
		return;
	}

	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'crrg' ) . '">', '</nav>' );
		return;
	}
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'crrg' ) . '">';
		rank_math_the_breadcrumbs();
		echo '</nav>';
		return;
	}
	if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
		echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'crrg' ) . '">';
		seopress_display_breadcrumbs();
		echo '</nav>';
		return;
	}

	$trail = crrg_breadcrumb_trail();
	if ( count( $trail ) < 2 ) {
		return;
	}

	$last = count( $trail ) - 1;
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'crrg' ) . '"><ol class="breadcrumbs__list">';
	foreach ( $trail as $index => $crumb ) {
		echo '<li class="breadcrumbs__item">';
		if ( $index === $last || empty( $crumb['url'] ) ) {
			echo '<span aria-current="page">' . esc_html( $crumb['label'] ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['label'] ) . '</a>';
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}

/**
 * Build the breadcrumb trail.
 *
 * @return array List of array( label, url ).
 */
function crrg_breadcrumb_trail() {
	$trail = array(
		array(
			'label' => __( 'Home', 'crrg' ),
			'url'   => home_url( '/' ),
		),
	);

	$posts_page = (int) get_option( 'page_for_posts' );
	$blog_crumb = $posts_page ? array(
		'label' => get_the_title( $posts_page ),
		'url'   => get_permalink( $posts_page ),
	) : null;

	if ( is_home() ) {
		$trail[] = array(
			'label' => $posts_page ? get_the_title( $posts_page ) : __( 'Latest News', 'crrg' ),
			'url'   => '',
		);
	} elseif ( is_singular( 'post' ) ) {
		if ( $blog_crumb ) {
			$trail[] = $blog_crumb;
		}
		$categories = get_the_category( get_queried_object_id() );
		if ( $categories ) {
			$category = $categories[0];
			foreach ( array_reverse( get_ancestors( $category->term_id, 'category' ) ) as $ancestor ) {
				$trail[] = array(
					'label' => get_cat_name( $ancestor ),
					'url'   => get_category_link( $ancestor ),
				);
			}
			$trail[] = array(
				'label' => $category->name,
				'url'   => get_category_link( $category ),
			);
		}
		$trail[] = array(
			'label' => single_post_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor ) {
			$trail[] = array(
				'label' => get_the_title( $ancestor ),
				'url'   => get_permalink( $ancestor ),
			);
		}
		$trail[] = array(
			'label' => single_post_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_singular() ) {
		$post_type = get_post_type_object( get_post_type( get_queried_object_id() ) );
		if ( $post_type && $post_type->has_archive ) {
			$trail[] = array(
				'label' => $post_type->labels->name,
				'url'   => get_post_type_archive_link( $post_type->name ),
			);
		}
		$trail[] = array(
			'label' => single_post_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $blog_crumb && ( is_category() || is_tag() ) ) {
			$trail[] = $blog_crumb;
		}
		if ( $term && is_taxonomy_hierarchical( $term->taxonomy ) ) {
			foreach ( array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) ) as $ancestor ) {
				$ancestor_term = get_term( $ancestor, $term->taxonomy );
				$trail[]       = array(
					'label' => $ancestor_term->name,
					'url'   => get_term_link( $ancestor_term ),
				);
			}
		}
		$trail[] = array(
			'label' => single_term_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_search() ) {
		$trail[] = array(
			/* translators: %s: search query. */
			'label' => sprintf( __( 'Search: %s', 'crrg' ), get_search_query() ),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		$trail[] = array(
			'label' => __( 'Page not found', 'crrg' ),
			'url'   => '',
		);
	} elseif ( is_archive() ) {
		if ( $blog_crumb && ! is_post_type_archive() ) {
			$trail[] = $blog_crumb;
		}
		$trail[] = array(
			'label' => wp_strip_all_tags( get_the_archive_title() ),
			'url'   => '',
		);
	}

	/**
	 * Filters the breadcrumb trail.
	 *
	 * @param array $trail List of array( label, url ).
	 */
	return apply_filters( 'crrg_breadcrumb_trail', $trail );
}
