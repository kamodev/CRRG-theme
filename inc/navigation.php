<?php
/**
 * Menu helpers: accessible sub-menu toggles and menu output.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add a toggle button after parent items in the primary and mobile menus so
 * sub-menus can be opened by keyboard and touch.
 *
 * @param string   $output Item output.
 * @param WP_Post  $item   Menu item.
 * @param int      $depth  Depth.
 * @param stdClass $args   wp_nav_menu() args.
 * @return string
 */
function crrg_submenu_toggle( $output, $item, $depth, $args ) {
	if ( empty( $args->theme_location ) || ! in_array( $args->theme_location, array( 'primary', 'mobile', 'landing' ), true ) ) {
		return $output;
	}

	if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$output .= sprintf(
			'<button type="button" class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">%1$s</span>%2$s</button>',
			/* translators: %s: parent menu item title. */
			esc_html( sprintf( __( 'Show sub-menu for %s', 'crrg' ), wp_strip_all_tags( $item->title ) ) ),
			crrg_icon( 'chevron-down', array( 'size' => 16 ) )
		);
	}

	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'crrg_submenu_toggle', 10, 4 );

/**
 * Print the primary navigation.
 */
function crrg_primary_menu() {
	if ( ! has_nav_menu( 'primary' ) ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			printf(
				'<nav class="main-navigation main-navigation--empty" aria-label="%1$s"><a href="%2$s">%3$s</a></nav>',
				esc_attr__( 'Primary', 'crrg' ),
				esc_url( admin_url( 'nav-menus.php' ) ),
				esc_html__( 'Add a Primary Menu', 'crrg' )
			);
		}
		return;
	}
	?>
	<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary', 'crrg' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'menu_class'     => 'menu primary-menu',
				'container'      => false,
				'depth'          => 4,
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
	<?php
}

/**
 * Print the mobile navigation panel.
 */
function crrg_mobile_menu() {
	$location = has_nav_menu( 'mobile' ) ? 'mobile' : ( has_nav_menu( 'primary' ) ? 'primary' : '' );

	if ( crrg_is_landing() ) {
		$location = has_nav_menu( 'landing' ) ? 'landing' : '';
	}

	if ( ! $location ) {
		return;
	}
	?>
	<div id="mobile-navigation" class="mobile-navigation" hidden>
		<div class="mobile-navigation__header">
			<span class="mobile-navigation__title"><?php esc_html_e( 'Menu', 'crrg' ); ?></span>
			<button type="button" class="header-icon-button mobile-navigation__close">
				<?php echo crrg_icon( 'close', array( 'size' => 24 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'crrg' ); ?></span>
			</button>
		</div>
		<nav class="mobile-navigation__nav" aria-label="<?php esc_attr_e( 'Mobile', 'crrg' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => $location,
					'menu_id'        => 'mobile-menu',
					'menu_class'     => 'menu mobile-menu',
					'container'      => false,
					'depth'          => 4,
					'fallback_cb'    => false,
					'item_spacing'   => 'discard',
				)
			);
			?>
		</nav>
		<?php if ( crrg_get_option( 'header_cta_url' ) && crrg_get_option( 'header_cta_text' ) ) : ?>
			<div class="mobile-navigation__cta"><?php crrg_header_cta(); ?></div>
		<?php endif; ?>
		<div class="mobile-navigation__social"><?php crrg_social_links( 'mobile' ); ?></div>
	</div>
	<div class="mobile-navigation-backdrop" hidden></div>
	<?php
}

/**
 * Drop menu item IDs inside the mobile panel.
 *
 * The panel usually renders the primary menu a second time, which would
 * otherwise duplicate every menu item ID on the page.
 *
 * @param string   $id   Item ID attribute.
 * @param WP_Post  $item Menu item.
 * @param stdClass $args wp_nav_menu() args.
 * @return string
 */
function crrg_mobile_menu_item_id( $id, $item, $args ) {
	if ( isset( $args->menu_id ) && 'mobile-menu' === $args->menu_id ) {
		return '';
	}

	return $id;
}
add_filter( 'nav_menu_item_id', 'crrg_mobile_menu_item_id', 10, 3 );

/**
 * Simple flat menu (top bar, footer).
 *
 * @param string $location Menu location.
 * @param string $label    Accessible label.
 */
function crrg_simple_menu( $location, $label ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	?>
	<nav class="<?php echo esc_attr( $location ); ?>-navigation" aria-label="<?php echo esc_attr( $label ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'menu_class'     => 'menu ' . $location . '-menu',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		?>
	</nav>
	<?php
}
