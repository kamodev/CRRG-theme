<?php
/**
 * Customizer heading control.
 *
 * Loaded from crrg_customize_register() so WP_Customize_Control exists.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * A heading used to group related controls inside a section.
 */
class CRRG_Heading_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'crrg-heading';

	/**
	 * Render the heading.
	 */
	protected function render_content() {
		if ( $this->label ) {
			echo '<h4 class="crrg-customize-heading">' . esc_html( $this->label ) . '</h4>';
		}
		if ( $this->description ) {
			echo '<p class="description">' . esc_html( $this->description ) . '</p>';
		}
	}
}
