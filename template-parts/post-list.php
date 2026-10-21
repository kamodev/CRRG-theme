<?php
/**
 * The loop for post lists, wrapped according to the blog style option.
 *
 * @package CRRG
 */

$crrg_style   = crrg_get_option( 'blog_style' );
$crrg_classes = array( 'post-list', 'post-list--' . $crrg_style );

if ( 'grid' === $crrg_style ) {
	$crrg_classes[] = 'post-list--cols-' . crrg_blog_columns();
}
?>
<div class="<?php echo esc_attr( implode( ' ', $crrg_classes ) ); ?>">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', is_search() ? 'search' : get_post_format() );
	}
	?>
</div>
