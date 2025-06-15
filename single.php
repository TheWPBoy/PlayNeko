<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package PlayNeko
 */

get_header();

$duration = get_post_meta( get_the_ID(), 'duration', true );
$video_url = get_post_meta( get_the_ID(), 'video_url', true );
?>
	<main id="primary" class="site-main" data-wp-template-part="main">
		<div class="main-content">
		<div class="video-container">
		<div id="video-container"
			 data-wp-interactive="playneko"
			 data-wp-template-part="main"
			 data-video-url="<?php echo esc_url($video_url); ?>"
			 data-poster="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>">

			</div>
			<div class="video-meta">


			<a href="#comments" class="comments btnff" aria-label="Total Number of Comments"  title="Total Number of Comments">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12c0 1.6.376 3.112 1.043 4.453c.178.356.237.763.134 1.148l-.595 2.226a1.3 1.3 0 0 0 1.591 1.592l2.226-.596a1.63 1.63 0 0 1 1.149.133A9.96 9.96 0 0 0 12 22m-4-8.75a.75.75 0 0 0 0 1.5h5.5a.75.75 0 0 0 0-1.5zm-.75-2.75A.75.75 0 0 1 8 9.75h8a.75.75 0 0 1 0 1.5H8a.75.75 0 0 1-.75-.75" clip-rule="evenodd"/></svg>
				<span class="text-lebel"><?php comments_number('0 Comments', '1 Comment', '% Comments'); ?></span>
				</a>

				<a href="<?php echo esc_url( $video_url ); ?>" class="download-btn btnff" aria-label="Download This Video"  title="Download This Video">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M2 12c0-4.714 0-7.071 1.464-8.536C4.93 2 7.286 2 12 2s7.071 0 8.535 1.464C22 4.93 22 7.286 22 12s0 7.071-1.465 8.535C19.072 22 16.714 22 12 22s-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12m10-5.75a.75.75 0 0 1 .75.75v5.19l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l1.72 1.72V7a.75.75 0 0 1 .75-.75m-4 10a.75.75 0 0 0 0 1.5h8a.75.75 0 0 0 0-1.5z" clip-rule="evenodd"/></svg>
				<span class="text-lebel">Donwload</span>
				</a>

				<a href="#" class="like-btn <?php echo playneko_user_has_liked() ? 'active' : ''; ?> btnff" 
					data-post-id="<?php echo get_the_ID(); ?>" 
					title="<?php _e('Like this video', 'playneko'); ?>"
					role="button"
					aria-label="Like this video">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2 9.137C2 14 6.02 16.591 8.962 18.911C10 19.729 11 20.5 12 20.5s2-.77 3.038-1.59C17.981 16.592 22 14 22 9.138S16.5.825 12 5.501C7.5.825 2 4.274 2 9.137"/></svg>
				<span class="likes-count"><?php echo playneko_get_likes_count(); ?></span>
				<span class="text-lebel">Likes</span>
			</a>
			
			<a href="#" class="share-btn  btnff" 
					data-post-id="<?php echo get_the_ID(); ?>"
					data-title="<?php echo esc_attr(get_the_title()); ?>"
					data-url="<?php echo esc_url(get_permalink()); ?>"
					title="<?php _e('Share this video', 'playneko'); ?>"
					role="button"
					aria-label="Share this video">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M3.464 20.536C4.93 22 7.286 22 12 22s7.071 0 8.535-1.465C22 19.072 22 16.714 22 12s0-7.071-1.465-8.536C19.072 2 16.714 2 12 2S4.929 2 3.464 3.464C2 4.93 2 7.286 2 12s0 7.071 1.464 8.535M9.5 8.75A3.25 3.25 0 1 0 12.75 12a.75.75 0 0 1 1.5 0A4.75 4.75 0 1 1 9.5 7.25a.75.75 0 0 1 0 1.5M17.75 12a3.25 3.25 0 0 1-3.25 3.25a.75.75 0 0 0 0 1.5A4.75 4.75 0 1 0 9.75 12a.75.75 0 0 0 1.5 0a3.25 3.25 0 0 1 6.5 0" clip-rule="evenodd"/></svg>
				<span class="shares-count"><?php echo playneko_get_shares_count(); ?></span>
				<span class="text-lebel">Share</span>
			</a>
			</div>
		</div>
		<div class="entry-content">
			<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<div class="tags">
				<?php 
				// Get categories
				$categories = get_the_category();
				if (!empty($categories)) {
					foreach ($categories as $category) {
						echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-tag" data-wp-interactive="">' . esc_html($category->name) . '</a>';
					}
				}
				
				// Get models
				$models = get_the_terms(get_the_ID(), 'model');
				if (!empty($models) && !is_wp_error($models)) {
					foreach ($models as $model) {
						echo '<a href="' . esc_url(get_term_link($model)) . '" class="model-tag" data-wp-interactive="">' . esc_html($model->name) . '</a>';
					}
				}
				
				// Get tags
				$tags = get_the_tags();
				if (!empty($tags)) {
					foreach ($tags as $tag) {
						echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="post-tag" data-wp-interactive="">' . esc_html($tag->name) . '</a>';
					}
				}
				?>
				</div>
			</div>
		<aside class="related-section">
			<div class="related-videos">
				<div class="videos">
					<?php
					// Get related posts based on categories
					$related_posts = new WP_Query( array(
						'category__in'   => wp_get_post_categories( get_the_ID() ),
						'post__not_in'   => array( get_the_ID() ),
						'posts_per_page' => 6,
						'orderby'        => 'rand'
					) );

					if ( $related_posts->have_posts() ) :
						while ( $related_posts->have_posts() ) : 
							$related_posts->the_post();
							
							// Use the same content-loop template as homepage
							get_template_part( 'template-parts/content', 'loop' );
							
						endwhile;
						wp_reset_postdata();
					else :
						// If no related posts by category, show latest posts
						$latest_posts = new WP_Query( array(
							'post__not_in'   => array( get_the_ID() ),
							'posts_per_page' => 5,
							'orderby'        => 'date',
							'order'          => 'DESC'
						) );
						
						if ( $latest_posts->have_posts() ) :
							while ( $latest_posts->have_posts() ) : 
								$latest_posts->the_post();
								get_template_part( 'template-parts/content', 'loop' );
							endwhile;
							wp_reset_postdata();
						endif;
					endif;
					?>
				</div>
			</div>
		</aside>
		
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>
		</div>
		<div class="sidebar-content">
			<?php get_sidebar(); ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();
