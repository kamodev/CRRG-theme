<?php
/**
 * Block pattern category and block styles.
 *
 * The patterns themselves live in /patterns and are registered by WordPress
 * automatically from their file headers.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the pattern category and custom block styles.
 */
function crrg_register_block_extras() {
	register_block_pattern_category(
		'crrg',
		array(
			'label'       => __( 'CRRG', 'crrg' ),
			'description' => __( 'Sections for an amateur radio club site: repeaters, nets, membership and more.', 'crrg' ),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'crrg-card',
			'label' => __( 'Card', 'crrg' ),
		)
	);
	register_block_style(
		'core/table',
		array(
			'name'  => 'crrg-frequencies',
			'label' => __( 'Frequency table', 'crrg' ),
		)
	);
	register_block_style(
		'core/list',
		array(
			'name'  => 'crrg-checklist',
			'label' => __( 'Checklist', 'crrg' ),
		)
	);
	register_block_style(
		'core/heading',
		array(
			'name'  => 'crrg-underline',
			'label' => __( 'Accent underline', 'crrg' ),
		)
	);
}
add_action( 'init', 'crrg_register_block_extras' );
