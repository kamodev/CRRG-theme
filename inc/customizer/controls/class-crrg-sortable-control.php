<?php
/**
 * Customizer sortable list control.
 *
 * Loaded from crrg_customize_register() so WP_Customize_Control exists.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Drag-and-drop list that stores its order as a comma separated string.
 */
class CRRG_Sortable_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'crrg-sortable';

	/**
	 * Render the list.
	 */
	protected function render_content() {
		$order = array_filter( explode( ',', (string) $this->value() ) );

		// Show known items in saved order, then any that are missing.
		$items = array();
		foreach ( $order as $key ) {
			if ( isset( $this->choices[ $key ] ) ) {
				$items[ $key ] = $this->choices[ $key ];
			}
		}
		$items += $this->choices;
		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php if ( $this->description ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>
		<ul class="crrg-sortable">
			<?php foreach ( $items as $key => $label ) : ?>
				<li class="crrg-sortable__item" data-value="<?php echo esc_attr( $key ); ?>">
					<span class="dashicons dashicons-menu" aria-hidden="true"></span>
					<span class="crrg-sortable__label"><?php echo esc_html( $label ); ?></span>
					<span class="crrg-sortable__buttons">
						<button type="button" class="button-link crrg-sortable__up" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: section name. */ __( 'Move %s up', 'crrg' ), $label ) ); ?>"><span class="dashicons dashicons-arrow-up-alt2" aria-hidden="true"></span></button>
						<button type="button" class="button-link crrg-sortable__down" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: section name. */ __( 'Move %s down', 'crrg' ), $label ) ); ?>"><span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span></button>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" class="crrg-sortable__value" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', array_keys( $items ) ) ); ?>" />
		<?php
	}
}
