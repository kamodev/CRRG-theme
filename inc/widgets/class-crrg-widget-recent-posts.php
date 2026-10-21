<?php
/**
 * Recent posts with thumbnails widget.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Recent posts with optional thumbnails and dates.
 */
class CRRG_Widget_Recent_Posts extends CRRG_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'crrg_recent_posts',
			__( 'CRRG: Recent Posts', 'crrg' ),
			array(
				'classname'                   => 'widget_crrg_recent_posts',
				'description'                 => __( 'Recent posts with thumbnails.', 'crrg' ),
				'customize_selective_refresh' => true,
				'show_instance_in_rest'       => true,
			)
		);
	}

	/**
	 * Fields.
	 *
	 * @return array
	 */
	protected function fields() {
		return array(
			'title'      => array(
				'type'    => 'text',
				'label'   => __( 'Title', 'crrg' ),
				'default' => __( 'Recent News', 'crrg' ),
			),
			'number'     => array(
				'type'    => 'number',
				'label'   => __( 'Number of posts', 'crrg' ),
				'default' => 4,
			),
			'category'   => array(
				'type'    => 'select',
				'label'   => __( 'Category', 'crrg' ),
				'default' => '0',
				'choices' => crrg_category_choices(),
			),
			'show_thumb' => array(
				'type'    => 'checkbox',
				'label'   => __( 'Show thumbnails', 'crrg' ),
				'default' => true,
			),
			'show_date'  => array(
				'type'    => 'checkbox',
				'label'   => __( 'Show dates', 'crrg' ),
				'default' => true,
			),
		);
	}

	/**
	 * Output.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Instance.
	 */
	public function widget( $args, $instance ) {
		$instance = $this->with_defaults( $instance );

		$query = new WP_Query(
			array(
				'posts_per_page'      => max( 1, min( 20, absint( $instance['number'] ) ) ),
				'cat'                 => absint( $instance['category'] ),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
			)
		);

		if ( ! $query->have_posts() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$this->title( $args, $instance );

		echo '<ul class="crrg-recent-posts">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<li class="crrg-recent-posts__item">';
			if ( $instance['show_thumb'] && has_post_thumbnail() ) {
				echo '<a class="crrg-recent-posts__thumb" href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">' . get_the_post_thumbnail( null, 'thumbnail', array( 'alt' => '' ) ) . '</a>';
			}
			echo '<span class="crrg-recent-posts__body">';
			echo '<a class="crrg-recent-posts__title" href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
			if ( $instance['show_date'] ) {
				echo '<time class="crrg-recent-posts__date" datetime="' . esc_attr( get_the_date( DATE_W3C ) ) . '">' . esc_html( get_the_date() ) . '</time>';
			}
			echo '</span></li>';
		}
		echo '</ul>';
		wp_reset_postdata();

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
