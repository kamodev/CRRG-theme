<?php
/**
 * Per post / page layout overrides ("CRRG Layout" box in the editor).
 *
 * Values are registered as post meta so they work in both the block editor
 * sidebar box and the classic editor.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta fields managed by the box.
 *
 * @return array Key => type.
 */
function crrg_meta_fields() {
	return array(
		'_crrg_layout'      => 'string',
		'_crrg_sidebar'     => 'string',
		'_crrg_hide_title'  => 'boolean',
		'_crrg_hide_banner' => 'boolean',
	);
}

/**
 * Sidebar choices for the per-post sidebar override.
 *
 * @return array
 */
function crrg_sidebar_choices() {
	return array(
		'default'          => __( 'Default', 'crrg' ),
		'sidebar-1'        => __( 'Primary Sidebar', 'crrg' ),
		'sidebar-page'     => __( 'Page Sidebar', 'crrg' ),
		'sidebar-custom-1' => __( 'Custom Sidebar 1', 'crrg' ),
		'sidebar-custom-2' => __( 'Custom Sidebar 2', 'crrg' ),
	);
}

/**
 * Register the meta keys.
 */
function crrg_register_meta() {
	foreach ( crrg_meta_fields() as $key => $type ) {
		register_meta(
			'post',
			$key,
			array(
				'type'              => $type,
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'boolean' === $type ? 'rest_sanitize_boolean' : 'sanitize_key',
				'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			)
		);
	}
}
add_action( 'init', 'crrg_register_meta' );

/**
 * Add the box to public post types.
 */
function crrg_add_meta_box() {
	$post_types = get_post_types( array( 'public' => true ) );
	unset( $post_types['attachment'] );

	add_meta_box(
		'crrg-layout',
		__( 'CRRG Layout', 'crrg' ),
		'crrg_render_meta_box',
		array_values( $post_types ),
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'crrg_add_meta_box' );

/**
 * Render the box.
 *
 * @param WP_Post $post Current post.
 */
function crrg_render_meta_box( $post ) {
	wp_nonce_field( 'crrg_save_meta', 'crrg_meta_nonce' );

	$layout      = get_post_meta( $post->ID, '_crrg_layout', true );
	$sidebar     = get_post_meta( $post->ID, '_crrg_sidebar', true );
	$hide_title  = get_post_meta( $post->ID, '_crrg_hide_title', true );
	$hide_banner = get_post_meta( $post->ID, '_crrg_hide_banner', true );
	?>
	<p>
		<label for="crrg-layout-select"><strong><?php esc_html_e( 'Layout', 'crrg' ); ?></strong></label><br />
		<select id="crrg-layout-select" name="crrg_layout" class="widefat">
			<?php foreach ( crrg_layout_choices( true ) as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $layout ? $layout : 'default', $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<span class="description"><?php esc_html_e( 'A layout page template (Page Attributes) takes priority over this setting.', 'crrg' ); ?></span>
	</p>
	<p>
		<label for="crrg-sidebar-select"><strong><?php esc_html_e( 'Sidebar to show', 'crrg' ); ?></strong></label><br />
		<select id="crrg-sidebar-select" name="crrg_sidebar" class="widefat">
			<?php foreach ( crrg_sidebar_choices() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $sidebar ? $sidebar : 'default', $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label>
			<input type="checkbox" name="crrg_hide_title" value="1" <?php checked( (bool) $hide_title ); ?> />
			<?php esc_html_e( 'Hide the title', 'crrg' ); ?>
		</label><br />
		<label>
			<input type="checkbox" name="crrg_hide_banner" value="1" <?php checked( (bool) $hide_banner ); ?> />
			<?php esc_html_e( 'Hide the title banner (show the title in the content instead)', 'crrg' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Save the box.
 *
 * @param int $post_id Post ID.
 */
function crrg_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['crrg_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['crrg_meta_nonce'] ) ), 'crrg_save_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$layout = isset( $_POST['crrg_layout'] ) ? sanitize_key( wp_unslash( $_POST['crrg_layout'] ) ) : 'default';
	if ( ! array_key_exists( $layout, crrg_layout_choices( true ) ) || 'default' === $layout ) {
		delete_post_meta( $post_id, '_crrg_layout' );
	} else {
		update_post_meta( $post_id, '_crrg_layout', $layout );
	}

	$sidebar = isset( $_POST['crrg_sidebar'] ) ? sanitize_key( wp_unslash( $_POST['crrg_sidebar'] ) ) : 'default';
	if ( ! array_key_exists( $sidebar, crrg_sidebar_choices() ) || 'default' === $sidebar ) {
		delete_post_meta( $post_id, '_crrg_sidebar' );
	} else {
		update_post_meta( $post_id, '_crrg_sidebar', $sidebar );
	}

	foreach ( array( 'hide_title', 'hide_banner' ) as $flag ) {
		if ( ! empty( $_POST[ 'crrg_' . $flag ] ) ) {
			update_post_meta( $post_id, '_crrg_' . $flag, true );
		} else {
			delete_post_meta( $post_id, '_crrg_' . $flag );
		}
	}
}
add_action( 'save_post', 'crrg_save_meta_box' );
