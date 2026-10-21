<?php
/**
 * Site footer.
 *
 * @package CRRG
 */

?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php
		if ( ! crrg_is_landing() ) {
			get_template_part( 'template-parts/footer/widgets' );
		}
		get_template_part( 'template-parts/footer/site-info' );
		?>
	</footer><!-- #colophon -->

	<?php if ( crrg_get_option( 'back_to_top' ) ) : ?>
		<a href="#page" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'crrg' ); ?>">
			<?php echo crrg_icon( 'arrow-up', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
