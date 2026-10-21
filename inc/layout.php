<?php
/**
 * Layout resolution: which layout, sidebar and title banner the current
 * request uses.
 *
 * Priority for singular content: page template > per-post "CRRG Layout"
 * box > Customizer default for the content type.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Page templates and the layout each one forces.
 *
 * @return array Template file => layout.
 */
function crrg_template_layouts() {
	return array(
		'page-templates/template-right-sidebar.php' => 'right-sidebar',
		'page-templates/template-left-sidebar.php'  => 'left-sidebar',
		'page-templates/template-both-sidebars.php' => 'both-sidebars',
		'page-templates/template-no-sidebar.php'    => 'no-sidebar',
		'page-templates/template-full-width.php'    => 'full-width',
		'page-templates/template-landing.php'       => 'full-width',
	);
}

/**
 * Read a CRRG post meta value for the queried object.
 *
 * @param string $key Meta key without the leading "_crrg_".
 * @return mixed
 */
function crrg_post_meta( $key ) {
	$post_id = get_queried_object_id();

	if ( ! $post_id || ! is_singular() ) {
		// The posts page is a page too, so it can carry overrides.
		if ( is_home() && ! is_front_page() ) {
			$post_id = (int) get_option( 'page_for_posts' );
		} else {
			return '';
		}
	}

	return get_post_meta( $post_id, '_crrg_' . $key, true );
}

/**
 * The layout requested for the current view, before checking widgets.
 *
 * @return string
 */
function crrg_get_requested_layout() {
	$layout = '';

	if ( is_singular() ) {
		$template  = get_page_template_slug( get_queried_object_id() );
		$templates = crrg_template_layouts();

		if ( $template && isset( $templates[ $template ] ) ) {
			$layout = $templates[ $template ];
		}
	}

	if ( ! $layout ) {
		$meta = crrg_post_meta( 'layout' );
		if ( $meta && 'default' !== $meta ) {
			$layout = $meta;
		}
	}

	if ( ! $layout ) {
		if ( is_front_page() && ! is_home() ) {
			$layout = 'no-sidebar';
		} elseif ( is_home() ) {
			$layout = crrg_get_option( 'layout_blog' );
		} elseif ( is_page() ) {
			$layout = crrg_get_option( 'layout_page' );
		} elseif ( is_singular() ) {
			$layout = crrg_get_option( 'layout_single' );
		} elseif ( is_search() ) {
			$layout = crrg_get_option( 'layout_search' );
		} elseif ( is_archive() ) {
			$layout = crrg_get_option( 'layout_archive' );
		} elseif ( is_404() ) {
			$layout = 'no-sidebar';
		} else {
			$layout = crrg_get_option( 'layout_blog' );
		}
	}

	if ( ! array_key_exists( $layout, crrg_layout_choices() ) ) {
		$layout = 'right-sidebar';
	}

	/**
	 * Filters the requested layout for the current view.
	 *
	 * @param string $layout right-sidebar | left-sidebar | both-sidebars | no-sidebar | full-width.
	 */
	return apply_filters( 'crrg_requested_layout', $layout );
}

/**
 * ID of the main sidebar for the current view.
 *
 * @return string
 */
function crrg_get_main_sidebar() {
	$choice = crrg_post_meta( 'sidebar' );

	if ( $choice && 'default' !== $choice && is_registered_sidebar( $choice ) ) {
		$sidebar = $choice;
	} elseif ( is_page() && is_active_sidebar( 'sidebar-page' ) ) {
		$sidebar = 'sidebar-page';
	} else {
		$sidebar = 'sidebar-1';
	}

	/**
	 * Filters the main sidebar ID for the current view.
	 *
	 * @param string $sidebar Sidebar ID.
	 */
	return apply_filters( 'crrg_main_sidebar', $sidebar );
}

/**
 * The layout actually used, falling back when a sidebar has no widgets so
 * no empty column is ever shown.
 *
 * @return string
 */
function crrg_get_layout() {
	static $cache = array();

	$key = get_queried_object_id() . '|' . ( is_singular() ? 's' : 'l' );
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}

	$layout    = crrg_get_requested_layout();
	$main      = is_active_sidebar( crrg_get_main_sidebar() );
	$secondary = is_active_sidebar( 'sidebar-secondary' );

	if ( 'both-sidebars' === $layout ) {
		if ( $main && ! $secondary ) {
			$layout = 'right-sidebar';
		} elseif ( ! $main && $secondary ) {
			$layout = 'left-sidebar-secondary';
		} elseif ( ! $main && ! $secondary ) {
			$layout = 'no-sidebar';
		}
	} elseif ( in_array( $layout, array( 'right-sidebar', 'left-sidebar' ), true ) && ! $main ) {
		$layout = 'no-sidebar';
	}

	/**
	 * Filters the final layout for the current view.
	 *
	 * @param string $layout Layout slug.
	 */
	$cache[ $key ] = apply_filters( 'crrg_layout', $layout );

	return $cache[ $key ];
}

/**
 * Whether the current layout shows any sidebar.
 *
 * @return bool
 */
function crrg_has_sidebar() {
	return ! in_array( crrg_get_layout(), array( 'no-sidebar', 'full-width' ), true );
}

/**
 * Whether the current page uses the Landing Page template.
 *
 * @return bool
 */
function crrg_is_landing() {
	return is_singular() && 'page-templates/template-landing.php' === get_page_template_slug( get_queried_object_id() );
}

/**
 * Whether the title banner is shown for the current view.
 *
 * @return bool
 */
function crrg_show_page_banner() {
	$show = crrg_get_option( 'page_header_enable' )
		&& ! is_front_page()
		&& ! crrg_is_landing()
		&& ! crrg_post_meta( 'hide_banner' )
		&& ! crrg_post_meta( 'hide_title' );

	/**
	 * Filters whether the page title banner is displayed.
	 *
	 * @param bool $show Whether to show the banner.
	 */
	return (bool) apply_filters( 'crrg_show_page_banner', $show );
}

/**
 * Whether the entry title is printed inside the content area.
 *
 * @return bool
 */
function crrg_show_entry_title() {
	if ( crrg_post_meta( 'hide_title' ) || crrg_is_landing() ) {
		return false;
	}

	if ( is_front_page() ) {
		return false;
	}

	return ! crrg_show_page_banner();
}

/**
 * Open the content wrapper and the main column.
 *
 * @param string $main_class Extra classes for the main column.
 * @param string $tag        Element for the main column. Use "div" when the
 *                           page already has a <main> element.
 */
function crrg_layout_open( $main_class = '', $tag = 'main' ) {
	$layout = crrg_get_layout();
	$tag    = ( 'div' === $tag ) ? 'div' : 'main';
	?>
	<div class="content-wrap content-wrap--<?php echo esc_attr( $layout ); ?>">
		<?php crrg_content_widget_area( 'before-content', __( 'Before content', 'crrg' ) ); ?>
		<div class="content-layout content-layout--<?php echo esc_attr( $layout ); ?>">
			<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelisted above. ?><?php echo 'main' === $tag ? ' id="primary"' : ''; ?> class="<?php echo esc_attr( trim( 'site-main ' . $main_class ) ); ?>">
	<?php
}

/**
 * Close the main column, print the sidebars and close the wrapper.
 *
 * @param string $tag Element used in crrg_layout_open().
 */
function crrg_layout_close( $tag = 'main' ) {
	$layout = crrg_get_layout();
	?>
			<?php echo ( 'div' === $tag ) ? '</div>' : '</main><!-- #primary -->'; ?>
			<?php
			if ( in_array( $layout, array( 'right-sidebar', 'left-sidebar', 'both-sidebars' ), true ) ) {
				get_sidebar();
			}
			if ( in_array( $layout, array( 'both-sidebars', 'left-sidebar-secondary' ), true ) ) {
				get_sidebar( 'secondary' );
			}
			?>
		</div><!-- .content-layout -->
		<?php crrg_content_widget_area( 'after-content', __( 'After content', 'crrg' ) ); ?>
	</div><!-- .content-wrap -->
	<?php
}

/**
 * Print the Before / After Content widget areas (not on the front page or landing pages).
 *
 * @param string $id    Sidebar ID.
 * @param string $label Accessible label.
 */
function crrg_content_widget_area( $id, $label ) {
	if ( is_front_page() || crrg_is_landing() || ! is_active_sidebar( $id ) ) {
		return;
	}
	?>
	<aside class="widget-area widget-area--<?php echo esc_attr( $id ); ?>" aria-label="<?php echo esc_attr( $label ); ?>">
		<?php dynamic_sidebar( $id ); ?>
	</aside>
	<?php
}
