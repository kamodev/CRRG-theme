<?php
/**
 * Net schedule widget.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lists weekly nets with day/time and where to find them.
 */
class CRRG_Widget_Net_Schedule extends CRRG_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'crrg_net_schedule',
			__( 'CRRG: Net Schedule', 'crrg' ),
			array(
				'classname'                   => 'widget_crrg_net_schedule',
				'description'                 => __( 'Weekly net times and frequencies.', 'crrg' ),
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
			'title'     => array(
				'type'    => 'text',
				'label'   => __( 'Title', 'crrg' ),
				'default' => __( 'Weekly Nets', 'crrg' ),
			),
			'nets'      => array(
				'type'        => 'textarea',
				'label'       => __( 'Nets', 'crrg' ),
				'description' => __( 'One per line: Day & time | Net name | Frequency or talkgroup', 'crrg' ),
				'rows'        => 5,
				'default'     => "Sun 7:00 PM MT | Sunday Night Net | 146.670− PL 100.0\nSat 10:30 AM MT | Digital Learning Net | XLX022 H · TGIF 356",
			),
			'link_text' => array(
				'type'    => 'text',
				'label'   => __( 'Link text', 'crrg' ),
				'default' => __( 'Net details', 'crrg' ),
			),
			'link_url'  => array(
				'type'    => 'url',
				'label'   => __( 'Link URL', 'crrg' ),
				'default' => '/nets/',
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
		$rows     = $this->rows( $instance['nets'], 3 );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$this->title( $args, $instance );

		if ( $rows ) {
			echo '<ul class="crrg-nets">';
			foreach ( $rows as $row ) {
				list( $when, $name, $where ) = $row;
				echo '<li class="crrg-nets__item">';
				echo '<span class="crrg-nets__when">' . crrg_icon( 'clock', array( 'size' => 15 ) ) . esc_html( $when ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<span class="crrg-nets__name">' . esc_html( $name ) . '</span>';
				if ( $where ) {
					echo '<span class="crrg-nets__where">' . esc_html( $where ) . '</span>';
				}
				echo '</li>';
			}
			echo '</ul>';
		}

		if ( $instance['link_text'] && $instance['link_url'] ) {
			printf(
				'<a class="widget-more-link" href="%1$s">%2$s%3$s</a>',
				esc_url( crrg_resolve_url( $instance['link_url'] ) ),
				esc_html( $instance['link_text'] ),
				crrg_icon( 'arrow-right', array( 'size' => 16 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
