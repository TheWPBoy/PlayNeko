<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package PlayNeko
 */

get_header();
?>

	<main id="primary" class="site-main" data-wp-template-part="main">
		<div class="main-content">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'playneko' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

				<div class="videos">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
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
