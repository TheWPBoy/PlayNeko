<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package PlayNeko
 */

get_header();
?>

	<main id="primary" class="site-main" data-wp-template-part="main">
		<div class="main-content">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

				<div class="videos">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
						get_template_part( 'template-parts/content', 'loop' );

			endwhile;
					?>
				</div>
				
				<?php the_posts_pagination(); ?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>
		</div>
		<div class="sidebar-content">
			<?php get_sidebar(); ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();
