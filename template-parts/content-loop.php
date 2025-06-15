<?php
$duration = get_post_meta( get_the_ID(), 'duration', true );
?>
<a id="post-<?php the_ID(); ?>" class="video" href="<?php the_permalink(); ?>" data-wp-interactive aria-hidden="true" tabindex="-1">
	<div class="video-thumbnail">
		<?php the_post_thumbnail(); ?>
		<?php if ( $duration ) : ?>
			<span class="playposts-duration"><?php echo esc_html( $duration ); ?></span>
		<?php endif; ?>
	</div>
	<h2><?php the_title(); ?></h2>
	<div class="entry-meta">
		<span class="entry-date"><?php echo get_the_date(); ?></span>
		<span class="entry-comments"><?php comments_number('0 Comments', '1 Comment', '% Comments'); ?></span>
		
	</div>
</a>