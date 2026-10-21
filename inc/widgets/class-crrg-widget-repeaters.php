<?php
/**
 * Repeaters widget.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lists repeaters with frequency, offset and tone.
 */
class CRRG_Widget_Repeaters extends CRRG_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'crrg_repeaters',
			__( 'CRRG: Repeaters', 'crrg' ),
			array(
				'classname'                   => 'widget_crrg_repeaters',
				'description'                 => __( 'Repeater frequencies, offsets and tones.', 'crrg' ),
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
				'default' => __( 'Repeaters', 'crrg' ),
			),
			'repeaters' => array(
				'type'        => 'textarea',
				'label'       => __( 'Repeaters', 'crrg' ),
				'description' => __( 'One per line: Name | Output MHz | Offset | Tone', 'crrg' ),
				'rows'        => 6,
				'default'     => "Mestaa’ėhehe Mtn | 146.670 | − | 100.0\nSmokey Hill 2 m | 145.400 | − | 100.0\nSmokey Hill 70 cm | 448.500 | − | 100.0",
			),
			'link_text' => array(
				'type'    => 'text',
				'label'   => __( 'Link text', 'crrg' ),
				'default' => __( 'Coverage & details', 'crrg' ),
			),
			'link_url'  => array(
				'type'    => 'url',
				'label'   => __( 'Link URL', 'crrg' ),
				'default' => '/repeaters/',
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
		$rows     = $this->rows( $instance['repeaters'], 4 );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$this->title( $args, $instance );

		if ( $rows ) {
			echo '<ul class="crrg-repeaters">';
			foreach ( $rows as $row ) {
				list( $name, $freq, $offset, $tone ) = $row;
				echo '<li class="crrg-repeaters__item">';
				echo '<span class="crrg-repeaters__icon">' . crrg_icon( 'tower', array( 'size' => 22 ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<span class="crrg-repeaters__body">';
				echo '<span class="crrg-repeaters__freq">' . esc_html( $freq ) . '<span class="crrg-repeaters__offset">' . esc_html( $offset ) . '</span></span>';
				echo '<span class="crrg-repeaters__name">' . esc_html( $name ) . '</span>';
				if ( $tone ) {
					/* translators: %s: CTCSS tone in Hz. */
					echo '<span class="crrg-repeaters__tone">' . esc_html( sprintf( __( 'PL %s Hz', 'crrg' ), $tone ) ) . '</span>';
				}
				echo '</span></li>';
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
