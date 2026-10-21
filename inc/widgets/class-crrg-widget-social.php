<?php
/**
 * Social icons widget.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shows the icons configured under CRRG Theme Options > Social Links.
 */
class CRRG_Widget_Social extends CRRG_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'crrg_social',
			__( 'CRRG: Social Icons', 'crrg' ),
			array(
				'classname'                   => 'widget_crrg_social',
				'description'                 => __( 'Social icons from Customizer > CRRG Theme Options > Social Links.', 'crrg' ),
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
			'title' => array(
				'type'    => 'text',
				'label'   => __( 'Title', 'crrg' ),
				'default' => __( 'Follow Us', 'crrg' ),
			),
			'text'  => array(
				'type'    => 'textarea',
				'label'   => __( 'Text above the icons', 'crrg' ),
				'html'    => true,
				'default' => '',
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

		if ( ! crrg_get_social_links() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$this->title( $args, $instance );

		if ( $instance['text'] ) {
			echo '<p>' . wp_kses( $instance['text'], crrg_allowed_inline_html() ) . '</p>';
		}

		crrg_social_links( 'widget' );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
