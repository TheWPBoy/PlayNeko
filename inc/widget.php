<?php
/**
 * Custom PlayPosts Widget
 */

class PlayPosts_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'playposts_widget',
            __('PlayPosts', 'playneko'),
            array( 'description' => __( 'Display posts with thumbnails and sorting options', 'playneko' ), )
        );
    }

    /**
     * Front-end display of widget.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        // Get widget settings
        $number_posts = ! empty( $instance['number_posts'] ) ? $instance['number_posts'] : 5;
        $sort_by = ! empty( $instance['sort_by'] ) ? $instance['sort_by'] : 'recent';
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : true;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        // Build query arguments
        $query_args = array(
            'posts_per_page' => $number_posts,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true
        );

        // Handle sorting options
        switch ( $sort_by ) {
            case 'same_category':
                if ( is_single() ) {
                    $categories = wp_get_post_categories( get_the_ID() );
                    if ( ! empty( $categories ) ) {
                        $query_args['category__in'] = $categories;
                        $query_args['post__not_in'] = array( get_the_ID() );
                    }
                }
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
            
            case 'random':
                $query_args['orderby'] = 'rand';
                break;
            
            case 'title':
                $query_args['orderby'] = 'title';
                $query_args['order'] = 'ASC';
                break;
            
            case 'recent':
            default:
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
        }

        // Execute query
        $posts_query = new WP_Query( $query_args );

        if ( $posts_query->have_posts() ) : ?>
            <div class="playposts-widget">
                <ul class="playposts-list">
                    <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
                        <li class="playposts-item">
                            <a href="<?php the_permalink(); ?>" class="playposts-link" data-wp-interactive>
                                <?php if ( $show_thumbnail && has_post_thumbnail() ) : ?>
                                    <div class="playposts-thumbnail">
                                        <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'playposts-image' ) ); ?>
                                        <?php 
                                        $video_duration = get_post_meta( get_the_ID(), 'duration', true );
                                        if ( $video_duration ) : ?>
                                            <span class="playposts-duration"><?php echo esc_html( $video_duration ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="playposts-content">
                                    <h2 class="playposts-title"><?php the_title(); ?></h2>
                                    <?php if ( $show_date ) : ?>
                                        <span class="playposts-date"><?php echo get_the_date(); ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            
        <?php endif;

        wp_reset_postdata();
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts', 'playneko' );
        $number_posts = ! empty( $instance['number_posts'] ) ? $instance['number_posts'] : 5;
        $sort_by = ! empty( $instance['sort_by'] ) ? $instance['sort_by'] : 'recent';
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'playneko' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>"><?php _e( 'Number of posts:', 'playneko' ); ?></label> 
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_posts' ) ); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr( $number_posts ); ?>" size="3">
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>"><?php _e( 'Sort by:', 'playneko' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sort_by' ) ); ?>">
                <option value="recent" <?php selected( $sort_by, 'recent' ); ?>><?php _e( 'Recent Posts', 'playneko' ); ?></option>
                <option value="same_category" <?php selected( $sort_by, 'same_category' ); ?>><?php _e( 'Same Category (Single Page Only)', 'playneko' ); ?></option>
                <option value="random" <?php selected( $sort_by, 'random' ); ?>><?php _e( 'Random Posts', 'playneko' ); ?></option>
                <option value="title" <?php selected( $sort_by, 'title' ); ?>><?php _e( 'By Title (A-Z)', 'playneko' ); ?></option>
            </select>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php _e( 'Show thumbnails', 'playneko' ); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php _e( 'Show post date', 'playneko' ); ?></label>
        </p>
        
        <?php 
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number_posts'] = ( ! empty( $new_instance['number_posts'] ) ) ? absint( $new_instance['number_posts'] ) : 5;
        $instance['sort_by'] = ( ! empty( $new_instance['sort_by'] ) ) ? sanitize_text_field( $new_instance['sort_by'] ) : 'recent';
        $instance['show_thumbnail'] = ! empty( $new_instance['show_thumbnail'] );
        $instance['show_date'] = ! empty( $new_instance['show_date'] );

        return $instance;
    }
}

/**
 * Register PlayPosts Widget
 */
function register_playposts_widget() {
    register_widget( 'PlayPosts_Widget' );
}
add_action( 'widgets_init', 'register_playposts_widget' );
