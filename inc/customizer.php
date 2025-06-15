<?php
/**
 * PlayNeko Theme Customizer
 *
 * @package PlayNeko
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customization support for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function playneko_customize_register($wp_customize) {
    // Add Color Section
    $wp_customize->add_section('playneko_color_scheme', array(
        'title'    => __('Theme Colors', 'playneko'),
        'priority' => 30,
    ));

    // Primary Color
    $wp_customize->add_setting('primary_color', array(
        'default'           => '#064bca',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label'    => __('Primary Color', 'playneko'),
        'section'  => 'playneko_color_scheme',
        'settings' => 'primary_color',
    )));

    // Primary Light Color
    $wp_customize->add_setting('primary_light_color', array(
        'default'           => '#bcd4ff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_light_color', array(
        'label'    => __('Primary Light Color', 'playneko'),
        'section'  => 'playneko_color_scheme',
        'settings' => 'primary_light_color',
    )));

    // Text Color
    $wp_customize->add_setting('text_color', array(
        'default'           => '#0c0c11',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', array(
        'label'    => __('Text Color', 'playneko'),
        'section'  => 'playneko_color_scheme',
        'settings' => 'text_color',
    )));

    // Text Muted Color
    $wp_customize->add_setting('text_muted_color', array(
        'default'           => '#666666',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_muted_color', array(
        'label'    => __('Muted Text Color', 'playneko'),
        'section'  => 'playneko_color_scheme',
        'settings' => 'text_muted_color',
    )));

    // Add Layout Section
    $wp_customize->add_section('playneko_layout', array(
        'title'    => __('Layout Settings', 'playneko'),
        'priority' => 35,
    ));

    // Layout Type
    $wp_customize->add_setting('layout_type', array(
        'default'           => 'wide',
        'sanitize_callback' => 'playneko_sanitize_select',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('layout_type', array(
        'label'    => __('Layout Type', 'playneko'),
        'section'  => 'playneko_layout',
        'type'     => 'select',
        'choices'  => array(
            'wide'  => __('Wide Layout', 'playneko'),
            'boxed' => __('Boxed Layout', 'playneko'),
        ),
    ));

    // Container Width
    $wp_customize->add_setting('container_width', array(
        'default'           => 1200,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('container_width', array(
        'label'       => __('Container Width (px)', 'playneko'),
        'section'     => 'playneko_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1920,
            'step' => 10,
        ),
    ));

    // Video Grid Width
    $wp_customize->add_setting('video_grid_width', array(
        'default'           => 300,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('video_grid_width', array(
        'label'       => __('Video Grid Item Width (px)', 'playneko'),
        'section'     => 'playneko_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 100,
            'max'  => 600,
            'step' => 10,
        ),
    ));
}
add_action('customize_register', 'playneko_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function playneko_customize_preview_js() {
    wp_enqueue_script(
        'playneko-customizer',
        get_template_directory_uri() . '/js/customizer.js',
        array('customize-preview', 'jquery'),
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'playneko_customize_preview_js');

/**
 * Sanitize select option
 */
function playneko_sanitize_select($input, $setting) {
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Adjust brightness of a hex color
 */
function playneko_adjust_brightness($hex, $steps) {
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * Output custom CSS for colors and layout.
 */
function playneko_output_custom_colors() {
    $layout_type = get_theme_mod('layout_type', 'wide');
    $container_width = get_theme_mod('container_width', 1200);
    $video_grid_width = get_theme_mod('video_grid_width', 300);
    ?>
    <style type="text/css" id="playneko-colors">
        :root {
            --text: <?php echo esc_attr(get_theme_mod('text_color', '#0c0c11')); ?>;
            --text-muted: <?php echo esc_attr(get_theme_mod('text_muted_color', '#666666')); ?>;
            --primary: <?php echo esc_attr(get_theme_mod('primary_color', '#064bca')); ?>;
            --primary-light: <?php echo esc_attr(get_theme_mod('primary_light_color', '#bcd4ff')); ?>;
            --white: #fff;
            --black: #000;
        }

        /* Dark mode overrides */
        :root[class~="dark-mode"] {
            --text: #e1e1e1;
            --text-muted: #888;
            --primary: <?php echo esc_attr(get_theme_mod('primary_color', '#064bca')); ?>;
            --primary-light: <?php echo esc_attr(playneko_adjust_brightness(get_theme_mod('primary_color', '#064bca'), -20)); ?>;
            --white: #1a1a1a;
            --black: #ffffff;
        }

        <?php if ($layout_type === 'boxed') : ?>
        .site {
            max-width: <?php echo absint($container_width); ?>px;
            margin: auto;
            box-shadow: 0px 0px 20px #33333333;
        }
        <?php endif; ?>

        .videos {
            grid-template-columns: repeat(auto-fill, minmax(<?php echo absint($video_grid_width); ?>px, 1fr));
        }
    </style>
    <?php
}
add_action('wp_head', 'playneko_output_custom_colors');
