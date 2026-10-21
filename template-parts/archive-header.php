<?php
/**
 * In-content heading for lists when the title banner is turned off.
 *
 * @package CRRG
 */

if ( crrg_show_page_banner() || ( is_home() && is_front_page() ) ) {
	return;
}

$crrg_description = ( is_archive() && ! is_author() ) ? get_the_archive_description() : '';
?>
<header class="archive-header">
	<?php crrg_breadcrumbs(); ?>
	<h1 class="archive-header__title"><?php echo wp_kses_post( crrg_get_page_title() ); ?></h1>
	<?php if ( $crrg_description ) : ?>
		<div class="archive-header__description"><?php echo wp_kses_post( $crrg_description ); ?></div>
	<?php endif; ?>
</header>
<?php
if ( is_author() ) {
	get_template_part( 'template-parts/post/author-box' );
}
