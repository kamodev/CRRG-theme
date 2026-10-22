<?php
/**
 * Customizer slider control.
 *
 * Loaded from crrg_customize_register() so WP_Customize_Control exists.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * A range slider paired with a number box, kept in sync by
 * assets/js/customizer-controls.js. The slider holds the setting link.
 */
class CRRG_Range_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'crrg-range';

	/**
	 * Unit shown after the number box, e.g. "px" or "%".
	 *
	 * @var string
	 */
	public $unit = 'px';

	/**
	 * Render the control.
	 */
	protected function render_content() {
		$attrs = wp_parse_args(
			$this->input_attrs,
			array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			)
		);
		$id    = '_customize-input-' . $this->id;
		?>
		<label for="<?php echo esc_attr( $id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php if ( $this->description ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>
		<div class="crrg-range">
			<input
				type="range"
				id="<?php echo esc_attr( $id ); ?>"
				class="crrg-range__slider"
				min="<?php echo esc_attr( $attrs['min'] ); ?>"
				max="<?php echo esc_attr( $attrs['max'] ); ?>"
				step="<?php echo esc_attr( $attrs['step'] ); ?>"
				value="<?php echo esc_attr( $this->value() ); ?>"
				<?php $this->link(); ?>
			/>
			<input
				type="number"
				class="crrg-range__number"
				min="<?php echo esc_attr( $attrs['min'] ); ?>"
				max="<?php echo esc_attr( $attrs['max'] ); ?>"
				step="<?php echo esc_attr( $attrs['step'] ); ?>"
				value="<?php echo esc_attr( $this->value() ); ?>"
				aria-label="<?php echo esc_attr( $this->label ); ?>"
			/>
			<?php if ( $this->unit ) : ?>
				<span class="crrg-range__unit"><?php echo esc_html( $this->unit ); ?></span>
			<?php endif; ?>
			<button type="button" class="button-link crrg-range__reset" data-default="<?php echo esc_attr( $this->setting->default ); ?>">
				<?php esc_html_e( 'Reset', 'crrg' ); ?>
			</button>
		</div>
		<?php
	}
}
