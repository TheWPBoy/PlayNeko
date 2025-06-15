<?php
/**
 * PlayNeko functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package PlayNeko
 */

// === Start Output Buffering on Frontend ===
add_action('template_redirect', function () {
    if (!is_admin() && !defined('DOING_AJAX') && !headers_sent()) {
        ob_start();
    }
});


// === Inject Notice on Frontend if Credit Link Missing ===
add_action('shutdown', function () {
    if (is_admin() || defined('DOING_AJAX')) return;

    $html = ob_get_contents();
    if (!$html) return;

    $required_link = 'href="https://wpboy.net"';

    if (strpos($html, $required_link) === false) {
        $notice = '
        <div style="background:#ffeaea;color:#a00;border:2px dashed #d00;margin:0;padding:12px;text-align:center;font-weight:bold;font-size:16px;">
            ⚠️ Please add the footer credit to <a href="https://wpboy.net" target="_blank" style="color:#a00;">WPBoy</a> to continue using this free theme.
        </div>';

        // Inject right after <body> tag or at top if <body> not found
        if (preg_match('/<body[^>]*>/i', $html)) {
            $html = preg_replace('/<body([^>]*)>/i', '<body$1>' . $notice, $html, 1);
        } else {
            $html = $notice . $html;
        }

        ob_clean();
        echo $html;
    }
});


// === Admin Dashboard Notice if Footer Link Missing ===
add_action('admin_notices', function () {
    if (!current_user_can('manage_options')) return;

    $screen = get_current_screen();
    if (!$screen || $screen->base !== 'dashboard') return;

    $response = wp_remote_get(home_url(), ['timeout' => 5]);
    if (is_wp_error($response)) return;

    $body = wp_remote_retrieve_body($response);
    $required_link = 'href="https://wpboy.net"';

    if (strpos($body, $required_link) === false) {
        echo '<div class="notice notice-error"><p>
            ⚠️ <strong>WPBoy Theme Notice:</strong> Please add the footer credit to 
            <a href="https://wpboy.net" target="_blank">WPBoy</a> in order to use this free theme.
        </p></div>';
    }
});

add_action('wp_footer', function () {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let creditLink = document.querySelector('footer a[href="https://wpboy.net"]');

        if (!creditLink) {
            let warning = document.createElement('div');
            warning.innerHTML = '⚠️ Please add the footer credit to <a href="https://wpboy.net" target="_blank" style="color:#a00;text-decoration:underline;">WPBoy</a> to continue using this free theme.';
            warning.style = 'background:#ffeaea;color:#a00;border:2px dashed #d00;padding:12px;text-align:center;font-weight:bold;font-size:16px;z-index:99999;position:relative;';
            document.body.insertBefore(warning, document.body.firstChild);
        }
    });
    </script>
    <?php
});



if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function playneko_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on PlayNeko, use a find and replace
		* to change 'playneko' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'playneko', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'playneko' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'playneko_setup' );






/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function playneko_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'playneko_content_width', 640 );
}
add_action( 'after_setup_theme', 'playneko_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function playneko_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'playneko' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'playneko' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'playneko_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function playneko_scripts() {
	wp_enqueue_style( 'playneko-style', get_stylesheet_uri(), array(), _S_VERSION );

	// Enqueue your interactivity script
	wp_enqueue_script(
		'playneko-interactivity',
		get_template_directory_uri() . '/playneko-interactivity.js',
		array(),
		_S_VERSION,
		true
	);

	// Optional: only enqueue if needed
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'playneko_scripts' );


/**
 * Add data-wp-interactive to ALL navigation menu links
 */
function playneko_add_interactive_to_nav_links( $atts, $item, $args ) {
	error_log('Nav menu filter called for: ' . $item->title);
	$atts['data-wp-interactive'] = '';
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'playneko_add_interactive_to_nav_links', 10, 3 );



/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Widgets.
 */
require get_template_directory() . '/inc/widget.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Add Video Meta Box for Posts
 */
function playneko_add_video_meta_box() {
	add_meta_box(
		'playneko_video_meta',
		__( 'Video Information', 'playneko' ),
		'playneko_video_meta_box_callback',
		'post',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'playneko_add_video_meta_box' );

/**
 * Video Meta Box Callback
 */
function playneko_video_meta_box_callback( $post ) {
	// Add nonce for security
	wp_nonce_field( 'playneko_video_meta_nonce', 'playneko_video_meta_nonce' );
	
	// Get current values
	$video_url = get_post_meta( $post->ID, 'video_url', true );
	$video_duration = get_post_meta( $post->ID, 'duration', true );
	?>
	
	<style>
		.playneko-video-meta {
			padding: 20px 0;
		}
		.playneko-video-meta .form-table {
			margin: 0;
		}
		.playneko-video-meta .form-table th {
			width: 150px;
			padding: 15px 10px 15px 0;
			vertical-align: top;
		}
		.playneko-video-meta .form-table td {
			padding: 15px 10px;
		}
		.playneko-video-meta input[type="text"],
		.playneko-video-meta input[type="url"] {
			width: 100%;
			max-width: 500px;
			padding: 8px 12px;
			border: 1px solid #ddd;
			border-radius: 4px;
			font-size: 14px;
		}
		.playneko-video-meta input:focus {
			border-color: #0073aa;
			box-shadow: 0 0 0 1px #0073aa;
			outline: none;
		}
		.playneko-video-meta .description {
			margin-top: 8px;
			color: #666;
			font-style: italic;
		}
		.playneko-duration-detect {
			margin-top: 10px;
		}
		.playneko-duration-detect .button {
			margin-right: 10px;
		}
		.playneko-loading {
			display: none;
			color: #0073aa;
		}
		.playneko-success {
			color: #46b450;
			display: none;
		}
		.playneko-error {
			color: #dc3232;
			display: none;
		}
	</style>
	
	<div class="playneko-video-meta">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="playneko_video_url"><?php _e( 'Video URL', 'playneko' ); ?></label>
					</th>
					<td>
						<input 
							type="url" 
							id="playneko_video_url" 
							name="playneko_video_url" 
							value="<?php echo esc_attr( $video_url ); ?>" 
							placeholder="https://example.com/video.mp4"
						/>
						<p class="description">
							<?php _e( 'Enter the direct video URL (supports MP4, WebM, OGV formats)', 'playneko' ); ?>
						</p>
						<div class="playneko-duration-detect">
							<button type="button" class="button button-secondary" id="detect-duration">
								<?php _e( 'Auto-Detect Duration', 'playneko' ); ?>
							</button>
							<span class="playneko-loading"><?php _e( 'Detecting...', 'playneko' ); ?></span>
							<span class="playneko-success"><?php _e( 'Duration detected successfully!', 'playneko' ); ?></span>
							<span class="playneko-error"><?php _e( 'Could not detect duration. Please enter manually.', 'playneko' ); ?></span>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="playneko_video_duration"><?php _e( 'Duration', 'playneko' ); ?></label>
					</th>
					<td>
						<input 
							type="text" 
							id="playneko_video_duration" 
							name="playneko_video_duration" 
							value="<?php echo esc_attr( $video_duration ); ?>" 
							placeholder="5:30"
						/>
						<p class="description">
							<?php _e( 'Video duration in MM:SS format (e.g., 5:30 for 5 minutes 30 seconds)', 'playneko' ); ?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<script>
	jQuery(document).ready(function($) {
		$('#detect-duration').on('click', function() {
			var videoUrl = $('#playneko_video_url').val();
			
			if (!videoUrl) {
				alert('<?php _e( "Please enter a video URL first.", "playneko" ); ?>');
				return;
			}
			
			// Show loading state
			$('.playneko-loading').show();
			$('.playneko-success, .playneko-error').hide();
			$(this).prop('disabled', true);
			
			// Create video element to detect duration
			var video = document.createElement('video');
			video.preload = 'metadata';
			
			video.onloadedmetadata = function() {
				var duration = Math.round(video.duration);
				var minutes = Math.floor(duration / 60);
				var seconds = duration % 60;
				var formattedDuration = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
				
				$('#playneko_video_duration').val(formattedDuration);
				$('.playneko-loading').hide();
				$('.playneko-success').show();
				$('#detect-duration').prop('disabled', false);
				
				setTimeout(function() {
					$('.playneko-success').fadeOut();
				}, 3000);
			};
			
			video.onerror = function() {
				$('.playneko-loading').hide();
				$('.playneko-error').show();
				$('#detect-duration').prop('disabled', false);
				
				setTimeout(function() {
					$('.playneko-error').fadeOut();
				}, 5000);
			};
			
			video.src = videoUrl;
		});
		
		// Auto-detect when URL is pasted
		$('#playneko_video_url').on('paste', function() {
			setTimeout(function() {
				if ($('#playneko_video_url').val() && !$('#playneko_video_duration').val()) {
					$('#detect-duration').trigger('click');
				}
			}, 100);
		});
	});
	</script>
	<?php
}

/**
 * Save Video Meta Box Data
 */
function playneko_save_video_meta_box( $post_id ) {
	// Check if nonce is valid
	if ( ! isset( $_POST['playneko_video_meta_nonce'] ) || ! wp_verify_nonce( $_POST['playneko_video_meta_nonce'], 'playneko_video_meta_nonce' ) ) {
		return;
	}
	
	// Check if user has permission to edit post
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	// Don't save during autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	// Save video URL
	if ( isset( $_POST['playneko_video_url'] ) ) {
		$video_url = sanitize_url( $_POST['playneko_video_url'] );
		update_post_meta( $post_id, 'video_url', $video_url );
	}
	
	// Save video duration
	if ( isset( $_POST['playneko_video_duration'] ) ) {
		$video_duration = sanitize_text_field( $_POST['playneko_video_duration'] );
		update_post_meta( $post_id, 'duration', $video_duration );
	}
}
add_action( 'save_post', 'playneko_save_video_meta_box' );

/**
 * Helper function to get video URL
 */
function playneko_get_video_url( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	return get_post_meta( $post_id, 'video_url', true );
}

/**
 * Helper function to get video duration
 */
function playneko_get_video_duration( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	return get_post_meta( $post_id, 'duration', true );
}

/**
 * Like and Share System
 */

// Enqueue AJAX script for likes and shares
function playneko_enqueue_likes_script() {
	wp_enqueue_script(
		'playneko-likes',
		get_template_directory_uri() . '/js/likes.js',
		array('jquery'),
		_S_VERSION,
		true
	);
	
	// Localize script for AJAX
	wp_localize_script('playneko-likes', 'playneko_ajax', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('playneko_likes_nonce')
	));
}
add_action('wp_enqueue_scripts', 'playneko_enqueue_likes_script');

// AJAX handler for likes
function playneko_handle_like() {
	// Verify nonce
	if (!wp_verify_nonce($_POST['nonce'], 'playneko_likes_nonce')) {
		wp_die('Security check failed');
	}
	
	$post_id = intval($_POST['post_id']);
	$user_id = get_current_user_id();
	
	if (!$post_id) {
		wp_die('Invalid request');
	}
	
	// Get current likes count
	$likes = get_post_meta($post_id, 'likes_count', true) ?: 0;
	
	// Get users who liked this post
	$user_likes = get_post_meta($post_id, 'user_likes', true) ?: array();
	
	$user_key = $user_id ?: $_SERVER['REMOTE_ADDR']; // Use IP for guests
	
	$response = array();
	
	if (in_array($user_key, $user_likes)) {
		// Remove like
		$user_likes = array_diff($user_likes, array($user_key));
		$likes = max(0, $likes - 1);
		$response['action'] = 'removed_like';
		$response['user_liked'] = false;
	} else {
		// Add like
		$user_likes[] = $user_key;
		$likes++;
		$response['action'] = 'added_like';
		$response['user_liked'] = true;
	}
	
	// Update post meta
	update_post_meta($post_id, 'likes_count', $likes);
	update_post_meta($post_id, 'user_likes', array_values($user_likes));
	
	$response['likes'] = $likes;
	
	wp_send_json_success($response);
}
add_action('wp_ajax_playneko_like', 'playneko_handle_like');
add_action('wp_ajax_nopriv_playneko_like', 'playneko_handle_like');

// Share count tracking
function playneko_track_share() {
	if (!wp_verify_nonce($_POST['nonce'], 'playneko_likes_nonce')) {
		wp_die('Security check failed');
	}
	
	$post_id = intval($_POST['post_id']);
	if (!$post_id) {
		wp_die('Invalid request');
	}
	
	$shares = get_post_meta($post_id, 'shares_count', true) ?: 0;
	$shares++;
	update_post_meta($post_id, 'shares_count', $shares);
	
	wp_send_json_success(array('shares' => $shares));
}
add_action('wp_ajax_playneko_share', 'playneko_track_share');
add_action('wp_ajax_nopriv_playneko_share', 'playneko_track_share');

// Helper functions
function playneko_get_likes_count($post_id = null) {
	if (!$post_id) {
		$post_id = get_the_ID();
	}
	return get_post_meta($post_id, 'likes_count', true) ?: 0;
}

function playneko_get_shares_count($post_id = null) {
	if (!$post_id) {
		$post_id = get_the_ID();
	}
	return get_post_meta($post_id, 'shares_count', true) ?: 0;
}

function playneko_user_has_liked($post_id = null) {
	if (!$post_id) {
		$post_id = get_the_ID();
	}
	
	$user_id = get_current_user_id();
	$user_key = $user_id ?: $_SERVER['REMOTE_ADDR'];
	$user_likes = get_post_meta($post_id, 'user_likes', true) ?: array();
	
	return in_array($user_key, $user_likes);
}

/**
 * Initialize ArtPlayer for video posts
 */
function playneko_init_artplayer() {
    if (!is_single()) {
        return;
    }

    $video_url = playneko_get_video_url();
    if (empty($video_url)) {
        return;
    }

    // Enqueue ArtPlayer script

    // Initialize ArtPlayer
    wp_add_inline_script('artplayer', '
        document.addEventListener("DOMContentLoaded", function() {
            const art = new Artplayer({
                container: "#video-container",
                url: "' . esc_url($video_url) . '",
                poster: "' . get_the_post_thumbnail_url(get_the_ID(), 'full') . '",
                autoplay: false,
                theme: "#f00",
                volume: 0.5,
                isLive: false,
                setting: true,
                playbackRate: true,
                fullscreen: true,
                fullscreenWeb: false,
                pip: false,
                autoOrientation: true,
                lock: true,
                flip: true,
                aspectRatio: true,
                miniProgressBar: true,
                backdrop: true,
                playsInline: true,
                autoPlayback: true,
                airplay: true,
            });
        });
    ');
}
add_action('wp_enqueue_scripts', 'playneko_init_artplayer');

// Always enqueue ArtPlayer scripts for SPA/Interactivity compatibility
function playneko_enqueue_artplayer_init() {
    wp_enqueue_script('artplayer', 'https://cdn.jsdelivr.net/npm/artplayer/dist/artplayer.js', array(), '1.0.0', true);
    wp_enqueue_script('artplayer-init', get_template_directory_uri() . '/js/artplayer-init.js', array('artplayer'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'playneko_enqueue_artplayer_init');

/**
 * Add data-wp-interactive to pagination links
 */
function playneko_add_interactive_to_pagination_links( $link ) {
    return str_replace( '<a ', '<a data-wp-interactive="" ', $link );
}
add_filter( 'paginate_links', 'playneko_add_interactive_to_pagination_links' );

/**
 * Add data-wp-interactive to posts pagination links (comprehensive approach)
 */
function playneko_add_interactive_to_posts_pagination( $template, $class ) {
    // Add filter to modify pagination links
    add_filter( 'wp_link_pages_link', 'playneko_add_interactive_to_pagination_link' );
    add_filter( 'get_pagenum_link', 'playneko_add_interactive_to_pagenum_link' );
    
    return $template;
}
add_filter( 'navigation_markup_template', 'playneko_add_interactive_to_posts_pagination', 10, 2 );

function playneko_add_interactive_to_pagination_link( $link ) {
    return str_replace( '<a ', '<a data-wp-interactive="" ', $link );
}

function playneko_add_interactive_to_pagenum_link( $link ) {
    return $link; // This filter is for URL generation, not HTML
}

/**
 * Register Custom Taxonomy - Model
 */
function playneko_register_model_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Models', 'Taxonomy General Name', 'playneko' ),
        'singular_name'              => _x( 'Model', 'Taxonomy Singular Name', 'playneko' ),
        'menu_name'                  => __( 'Models', 'playneko' ),
        'all_items'                  => __( 'All Models', 'playneko' ),
        'parent_item'                => __( 'Parent Model', 'playneko' ),
        'parent_item_colon'          => __( 'Parent Model:', 'playneko' ),
        'new_item_name'              => __( 'New Model Name', 'playneko' ),
        'add_new_item'               => __( 'Add New Model', 'playneko' ),
        'edit_item'                  => __( 'Edit Model', 'playneko' ),
        'update_item'                => __( 'Update Model', 'playneko' ),
        'view_item'                  => __( 'View Model', 'playneko' ),
        'separate_items_with_commas' => __( 'Separate models with commas', 'playneko' ),
        'add_or_remove_items'        => __( 'Add or remove models', 'playneko' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'playneko' ),
        'popular_items'              => __( 'Popular Models', 'playneko' ),
        'search_items'               => __( 'Search Models', 'playneko' ),
        'not_found'                  => __( 'Not Found', 'playneko' ),
        'no_terms'                   => __( 'No models', 'playneko' ),
        'items_list'                 => __( 'Models list', 'playneko' ),
        'items_list_navigation'      => __( 'Models list navigation', 'playneko' ),
    );
    
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array(
            'slug'                   => 'model',
            'with_front'             => true,
            'hierarchical'           => false,
        ),
    );
    
    register_taxonomy( 'model', array( 'post' ), $args );
}
add_action( 'init', 'playneko_register_model_taxonomy', 0 );

