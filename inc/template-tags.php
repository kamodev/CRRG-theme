<?php
/**
 * Template tags used by the theme templates.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Logo, site title, call sign and tagline.
 */
function crrg_site_branding() {
	$show_title   = crrg_get_option( 'show_site_title' );
	$show_tagline = crrg_get_option( 'show_tagline' );
	$callsign     = crrg_get_option( 'header_callsign' );
	$description  = get_bloginfo( 'description', 'display' );
	$title_tag    = ( is_front_page() && is_home() ) ? 'h1' : 'p';
	?>
	<div class="site-branding">
		<?php if ( has_custom_logo() ) : ?>
			<div class="site-logo"><?php the_custom_logo(); ?></div>
		<?php endif; ?>

		<div class="site-branding__text">
			<<?php echo tag_escape( $title_tag ); ?> class="site-title<?php echo $show_title ? '' : ' screen-reader-text'; ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</<?php echo tag_escape( $title_tag ); ?>>

			<?php if ( $callsign || is_customize_preview() ) : ?>
				<span class="site-callsign"<?php echo $callsign ? '' : ' hidden'; ?>><?php echo esc_html( $callsign ); ?></span>
			<?php endif; ?>

			<?php if ( $description && ( $show_tagline || is_customize_preview() ) ) : ?>
				<p class="site-description<?php echo $show_tagline ? '' : ' screen-reader-text'; ?>"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_bloginfo( 'display' ). ?></p>
			<?php endif; ?>
		</div>
	</div><!-- .site-branding -->
	<?php
}

/**
 * Configured social links.
 *
 * @return array List of array( network, label, url ).
 */
function crrg_get_social_links() {
	$links = array();

	foreach ( crrg_social_networks() as $network => $label ) {
		$value = crrg_get_option( 'social_' . $network );
		if ( ! $value ) {
			continue;
		}
		$links[] = array(
			'network' => $network,
			'label'   => $label,
			'url'     => ( 'email' === $network ) ? 'mailto:' . antispambot( $value ) : $value,
		);
	}

	if ( crrg_get_option( 'social_rss' ) ) {
		$links[] = array(
			'network' => 'rss',
			'label'   => __( 'RSS feed', 'crrg' ),
			'url'     => get_feed_link(),
		);
	}

	/**
	 * Filters the social links.
	 *
	 * @param array $links List of array( network, label, url ).
	 */
	return apply_filters( 'crrg_social_links', $links );
}

/**
 * Print the social icon list.
 *
 * @param string $context Where the list appears (topbar, header, footer, widget).
 */
function crrg_social_links( $context = 'footer' ) {
	$links = crrg_get_social_links();

	if ( empty( $links ) ) {
		return;
	}

	$new_tab = crrg_get_option( 'social_new_tab' );
	?>
	<ul class="social-links social-links--<?php echo esc_attr( $context ); ?>">
		<?php foreach ( $links as $link ) : ?>
			<?php
			$external = $new_tab && 'email' !== $link['network'] && 'rss' !== $link['network'];
			$rel      = ( 'mastodon' === $link['network'] ) ? 'me noopener' : 'noopener';
			?>
			<li class="social-links__item">
				<a class="social-links__link social-links__link--<?php echo esc_attr( $link['network'] ); ?>" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $external ? ' target="_blank" rel="' . esc_attr( $rel ) . '"' : ''; ?>>
					<?php echo crrg_icon( $link['network'], array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="screen-reader-text">
						<?php
						echo esc_html( $link['label'] );
						if ( $external ) {
							echo ' ' . esc_html__( '(opens in a new tab)', 'crrg' );
						}
						?>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Print a button link.
 *
 * @param string $text    Button text.
 * @param string $url     Button URL.
 * @param string $classes Extra classes.
 * @param bool   $new_tab Open in a new tab.
 */
function crrg_button( $text, $url, $classes = '', $new_tab = false ) {
	if ( ! $text || ! $url ) {
		return;
	}

	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s</a>',
		esc_attr( trim( 'crrg-button ' . $classes ) ),
		esc_url( crrg_resolve_url( $url ) ),
		$new_tab ? ' target="_blank" rel="noopener"' : '',
		esc_html( $text )
	);
}

/**
 * Make a site-relative path ("/repeaters/") absolute for this install.
 *
 * @param string $url URL or path.
 * @return string
 */
function crrg_resolve_url( $url ) {
	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		return home_url( $url );
	}

	return $url;
}

/**
 * Header call to action button.
 */
function crrg_header_cta() {
	crrg_button(
		crrg_get_option( 'header_cta_text' ),
		crrg_get_option( 'header_cta_url' ),
		'crrg-button--secondary header-cta',
		crrg_get_option( 'header_cta_new_tab' )
	);
}

/**
 * Header search toggle button.
 */
function crrg_search_toggle() {
	if ( ! crrg_get_option( 'header_search' ) ) {
		return;
	}
	?>
	<button type="button" class="header-icon-button search-toggle" aria-controls="search-overlay" aria-expanded="false">
		<?php echo crrg_icon( 'search', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'crrg' ); ?></span>
	</button>
	<?php
}

/**
 * Mobile menu toggle button.
 */
function crrg_menu_toggle() {
	if ( ! has_nav_menu( 'primary' ) && ! has_nav_menu( 'mobile' ) ) {
		return;
	}
	?>
	<button type="button" class="header-icon-button menu-toggle" aria-controls="mobile-navigation" aria-expanded="false">
		<?php echo crrg_icon( 'menu', array( 'size' => 24 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="menu-toggle__label"><?php esc_html_e( 'Menu', 'crrg' ); ?></span>
	</button>
	<?php
}

/**
 * Title for the current view.
 *
 * @return string
 */
function crrg_get_page_title() {
	if ( is_home() ) {
		$page_for_posts = (int) get_option( 'page_for_posts' );
		$title          = $page_for_posts ? get_the_title( $page_for_posts ) : __( 'Latest News', 'crrg' );
	} elseif ( is_singular() ) {
		$title = single_post_title( '', false );
	} elseif ( is_search() ) {
		/* translators: %s: search query. */
		$title = sprintf( __( 'Search results for &ldquo;%s&rdquo;', 'crrg' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
	} elseif ( is_404() ) {
		$title = __( 'Page not found', 'crrg' );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
	} else {
		$title = get_bloginfo( 'name' );
	}

	/**
	 * Filters the title shown in the title banner.
	 *
	 * @param string $title Title HTML.
	 */
	return apply_filters( 'crrg_page_title', $title );
}

/**
 * Print the title banner for inner pages.
 */
function crrg_page_banner() {
	if ( ! crrg_show_page_banner() ) {
		return;
	}

	$style = '';
	if ( is_page() && crrg_get_option( 'page_header_featured' ) && has_post_thumbnail( get_queried_object_id() ) ) {
		$image = get_the_post_thumbnail_url( get_queried_object_id(), 'crrg-banner' );
		if ( $image ) {
			$style = ' style="background-image:url(' . esc_url( $image ) . ');"';
		}
	}

	$classes = array(
		'page-banner',
		'page-banner--' . crrg_get_option( 'page_header_size' ),
		'page-banner--align-' . crrg_get_option( 'page_header_align' ),
	);
	if ( $style || crrg_get_option( 'page_header_image' ) ) {
		$classes[] = 'page-banner--has-image';
	}

	$description = ( is_archive() && ! is_author() ) ? get_the_archive_description() : '';
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
		<div class="container page-banner__inner">
			<?php crrg_breadcrumbs(); ?>
			<h1 class="page-banner__title"><?php echo wp_kses_post( crrg_get_page_title() ); ?></h1>
			<?php if ( is_singular( 'post' ) && crrg_get_option( 'single_meta' ) ) : ?>
				<div class="page-banner__meta"><?php crrg_entry_meta(); ?></div>
			<?php elseif ( $description ) : ?>
				<div class="page-banner__description"><?php echo wp_kses_post( $description ); ?></div>
			<?php elseif ( is_page() && has_excerpt( get_queried_object_id() ) ) : ?>
				<p class="page-banner__description"><?php echo esc_html( get_the_excerpt( get_queried_object_id() ) ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Post meta line: date, author, categories, comments.
 *
 * @param bool $is_list Whether this is a post list (uses the Blog meta toggles).
 */
function crrg_entry_meta( $is_list = false ) {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	$items = array();

	if ( ! $is_list || crrg_get_option( 'meta_date' ) ) {
		$time = sprintf(
			'<time class="entry-date published" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time .= sprintf(
				'<time class="updated screen-reader-text" datetime="%1$s">%2$s</time>',
				esc_attr( get_the_modified_date( DATE_W3C ) ),
				esc_html( get_the_modified_date() )
			);
		}
		$items['date'] = crrg_icon( 'calendar', array( 'size' => 15 ) ) . '<span class="screen-reader-text">' . esc_html__( 'Posted on', 'crrg' ) . '</span> <a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time . '</a>';
	}

	if ( ( ! $is_list || crrg_get_option( 'meta_author' ) ) && get_the_author() ) {
		$items['author'] = crrg_icon( 'user', array( 'size' => 15 ) ) . '<span class="screen-reader-text">' . esc_html__( 'By', 'crrg' ) . '</span> <span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
	}

	if ( ( ! $is_list || crrg_get_option( 'meta_categories' ) ) && has_category() ) {
		/* translators: Used between list items, there is a space after the comma. */
		$items['categories'] = crrg_icon( 'folder', array( 'size' => 15 ) ) . '<span class="screen-reader-text">' . esc_html__( 'Posted in', 'crrg' ) . '</span> <span class="entry-meta__list">' . get_the_category_list( esc_html__( ', ', 'crrg' ) ) . '</span>';
	}

	if ( ( ! $is_list || crrg_get_option( 'meta_comments' ) ) && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		$items['comments'] = crrg_icon( 'comment', array( 'size' => 15 ) ) . ' <a href="' . esc_url( get_comments_link() ) . '">' . esc_html( sprintf( /* translators: %s: number of comments. */ _n( '%s comment', '%s comments', get_comments_number(), 'crrg' ), number_format_i18n( get_comments_number() ) ) ) . '</a>';
	}

	/**
	 * Filters the post meta items.
	 *
	 * @param array $items   Item key => HTML.
	 * @param bool  $is_list Whether this is a post list.
	 */
	$items = apply_filters( 'crrg_entry_meta', $items, $is_list );

	if ( empty( $items ) ) {
		return;
	}

	echo '<div class="entry-meta">';
	foreach ( $items as $key => $html ) {
		echo '<span class="entry-meta__item entry-meta__item--' . esc_attr( $key ) . '">' . $html . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above.
	}
	echo '</div>';
}

/**
 * Tags and edit link after single post content.
 */
function crrg_entry_footer() {
	if ( 'post' === get_post_type() && crrg_get_option( 'single_tags' ) ) {
		$tags = get_the_tag_list( '', '' );
		if ( $tags ) {
			echo '<div class="entry-tags">' . crrg_icon( 'tag', array( 'size' => 16 ) ) . '<span class="screen-reader-text">' . esc_html__( 'Tags:', 'crrg' ) . '</span>' . $tags . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	edit_post_link(
		sprintf(
			/* translators: %s: post title. */
			esc_html__( 'Edit %s', 'crrg' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		),
		'<span class="edit-link">',
		'</span>'
	);
}

/**
 * Featured image with a link on lists.
 *
 * @param string $size Image size.
 */
function crrg_post_thumbnail( $size = 'post-thumbnail' ) {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() && in_the_loop() && get_the_ID() === get_queried_object_id() ) :
		?>
		<figure class="post-thumbnail">
			<?php the_post_thumbnail( $size ); ?>
			<?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
				<figcaption class="wp-caption-text"><?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
			<?php endif; ?>
		</figure>
		<?php
	else :
		?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( $size, array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</a>
		<?php
	endif;
}

/**
 * Pagination for post lists.
 */
function crrg_pagination() {
	if ( 'prev-next' === crrg_get_option( 'pagination_type' ) ) {
		the_posts_navigation(
			array(
				'prev_text' => crrg_icon( 'arrow-left', array( 'size' => 16 ) ) . esc_html__( 'Older posts', 'crrg' ),
				'next_text' => esc_html__( 'Newer posts', 'crrg' ) . crrg_icon( 'arrow-right', array( 'size' => 16 ) ),
			)
		);
		return;
	}

	the_posts_pagination(
		array(
			'mid_size'  => 2,
			'prev_text' => crrg_icon( 'arrow-left', array( 'size' => 16 ) ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'crrg' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'crrg' ) . '</span>' . crrg_icon( 'arrow-right', array( 'size' => 16 ) ),
		)
	);
}

/**
 * Footer copyright line with {year} and {site} placeholders.
 *
 * @return string
 */
function crrg_copyright_text() {
	$text = crrg_get_option( 'footer_copyright' );
	$text = str_replace(
		array( '{year}', '{site}' ),
		array( wp_date( 'Y' ), get_bloginfo( 'name' ) ),
		$text
	);

	return wp_kses( $text, crrg_allowed_inline_html() );
}

/**
 * "Read more" link for post lists.
 */
function crrg_read_more() {
	$text = crrg_get_option( 'read_more_text' );

	if ( ! $text ) {
		return;
	}

	printf(
		'<a class="read-more" href="%1$s">%2$s<span class="screen-reader-text"> %3$s</span>%4$s</a>',
		esc_url( get_permalink() ),
		esc_html( $text ),
		/* translators: %s: post title, hidden accessibility text. */
		esc_html( sprintf( __( 'about %s', 'crrg' ), the_title_attribute( array( 'echo' => false ) ) ) ),
		crrg_icon( 'arrow-right', array( 'size' => 16 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
