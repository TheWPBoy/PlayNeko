<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PlayNeko
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<a href="<?php bloginfo('url'); ?>"> <?php bloginfo('name'); ?></a>
			<span class="sep"> | </span>
				<?php
				printf(
					'%1$s %2$s',
					'Made with ❤️ by',
					'<a href="' . esc_url( 'https://wpboy.net' ) . '" target="_blank">' . esc_html__( 'WPPoy', 'playneko' ) . '</a>'
				);
				?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
