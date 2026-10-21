<?php
/**
 * Contact info widget.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Club contact details: call sign, email, phone, meeting place.
 */
class CRRG_Widget_Contact extends CRRG_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'crrg_contact',
			__( 'CRRG: Contact Info', 'crrg' ),
			array(
				'classname'                   => 'widget_crrg_contact',
				'description'                 => __( 'Call sign, email, phone, address and meeting details.', 'crrg' ),
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
				'default' => __( 'Contact', 'crrg' ),
			),
			'intro'     => array(
				'type'    => 'textarea',
				'label'   => __( 'Intro text', 'crrg' ),
				'html'    => true,
				'default' => __( 'Questions about the repeaters, nets or membership? We would love to hear from you.', 'crrg' ),
			),
			'callsign'  => array(
				'type'    => 'text',
				'label'   => __( 'Club call sign', 'crrg' ),
				'default' => '',
			),
			'email'     => array(
				'type'    => 'email',
				'label'   => __( 'Email', 'crrg' ),
				'default' => '',
			),
			'phone'     => array(
				'type'    => 'text',
				'label'   => __( 'Phone', 'crrg' ),
				'default' => '',
			),
			'address'   => array(
				'type'    => 'textarea',
				'label'   => __( 'Mailing address', 'crrg' ),
				'rows'    => 3,
				'default' => '',
			),
			'meeting'   => array(
				'type'    => 'textarea',
				'label'   => __( 'Meetings', 'crrg' ),
				'rows'    => 3,
				'default' => '',
			),
			'link_text' => array(
				'type'    => 'text',
				'label'   => __( 'Link text', 'crrg' ),
				'default' => __( 'Contact us', 'crrg' ),
			),
			'link_url'  => array(
				'type'    => 'url',
				'label'   => __( 'Link URL', 'crrg' ),
				'default' => '/contact/',
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

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$this->title( $args, $instance );

		if ( $instance['intro'] ) {
			echo '<p>' . wp_kses( $instance['intro'], crrg_allowed_inline_html() ) . '</p>';
		}

		$items = array();
		if ( $instance['callsign'] ) {
			$items['radio'] = '<strong class="crrg-contact__callsign">' . esc_html( $instance['callsign'] ) . '</strong>';
		}
		if ( $instance['email'] ) {
			$email         = antispambot( $instance['email'] );
			$items['mail'] = '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
		}
		if ( $instance['phone'] ) {
			$items['phone'] = '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $instance['phone'] ) ) . '">' . esc_html( $instance['phone'] ) . '</a>';
		}
		if ( $instance['address'] ) {
			$items['map-pin'] = nl2br( esc_html( $instance['address'] ) );
		}
		if ( $instance['meeting'] ) {
			$items['calendar'] = nl2br( esc_html( $instance['meeting'] ) );
		}

		if ( $items ) {
			echo '<ul class="crrg-contact">';
			foreach ( $items as $icon => $html ) {
				echo '<li class="crrg-contact__item">' . crrg_icon( $icon, array( 'size' => 18 ) ) . '<span>' . $html . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
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
