<?php
/**
 * Call to action widget.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * A coloured box with text and a button.
 */
class CRRG_Widget_CTA extends CRRG_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'crrg_cta',
			__( 'CRRG: Call to Action', 'crrg' ),
			array(
				'classname'                   => 'widget_crrg_cta',
				'description'                 => __( 'A highlighted box with a heading, text and button.', 'crrg' ),
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
			'title'       => array(
				'type'    => 'text',
				'label'   => __( 'Title', 'crrg' ),
				'default' => __( 'Become a Member', 'crrg' ),
			),
			'text'        => array(
				'type'    => 'textarea',
				'label'   => __( 'Text', 'crrg' ),
				'html'    => true,
				'default' => __( 'Your dues keep the repeaters on the air for everyone.', 'crrg' ),
			),
			'button_text' => array(
				'type'    => 'text',
				'label'   => __( 'Button text', 'crrg' ),
				'default' => __( 'Join Today', 'crrg' ),
			),
			'button_url'  => array(
				'type'    => 'url',
				'label'   => __( 'Button URL', 'crrg' ),
				'default' => '/membership/',
			),
			'new_tab'     => array(
				'type'    => 'checkbox',
				'label'   => __( 'Open in a new tab', 'crrg' ),
				'default' => false,
			),
			'style'       => array(
				'type'    => 'select',
				'label'   => __( 'Colour', 'crrg' ),
				'default' => 'primary',
				'choices' => array(
					'primary'   => __( 'Primary', 'crrg' ),
					'secondary' => __( 'Secondary', 'crrg' ),
					'accent'    => __( 'Accent', 'crrg' ),
				),
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

		// Add the colour modifier to the widget wrapper.
		$before = str_replace( 'class="widget ', 'class="widget crrg-cta-box crrg-cta-box--' . esc_attr( $instance['style'] ) . ' ', $args['before_widget'] );

		echo $before; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$this->title( $args, $instance );

		if ( $instance['text'] ) {
			echo '<p class="crrg-cta-box__text">' . wp_kses( $instance['text'], crrg_allowed_inline_html() ) . '</p>';
		}

		$button_class = ( 'secondary' === $instance['style'] ) ? 'crrg-button--light' : 'crrg-button--secondary';
		if ( 'accent' === $instance['style'] ) {
			$button_class = 'crrg-button--dark';
		}
		crrg_button( $instance['button_text'], $instance['button_url'], $button_class, (bool) $instance['new_tab'] );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
