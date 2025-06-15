/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 */

(function($) {
    'use strict';

    // Site title and description.
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });
    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Color Settings
    const colorSettings = {
        'primary_color': '--primary',
        'primary_light_color': '--primary-light',
        'text_color': '--text',
        'text_muted_color': '--text-muted'
    };

    // Bind all color settings
    Object.entries(colorSettings).forEach(([setting, cssVar]) => {
        wp.customize(setting, function(value) {
            value.bind(function(newval) {
                document.documentElement.style.setProperty(cssVar, newval);

                // Special handling for dark mode primary light color
                if (setting === 'primary_color' && document.documentElement.classList.contains('dark-mode')) {
                    const darkModeLight = adjustBrightness(newval, -20);
                    document.documentElement.style.setProperty('--primary-light', darkModeLight);
                }
            });
        });
    });

    // Layout Type
    wp.customize('layout_type', function(value) {
        value.bind(function(newval) {
            const $site = $('.site');
            const width = wp.customize('container_width').get();

            if (newval === 'boxed') {
                $site.css({
                    'max-width': width + 'px',
                    'margin': 'auto',
                    'box-shadow': '0px 0px 20px #33333333'
                });
            } else {
                $site.css({
                    'max-width': 'none',
                    'margin': '0',
                    'box-shadow': 'none'
                });
            }
        });
    });

    // Container Width
    wp.customize('container_width', function(value) {
        value.bind(function(newval) {
            if (wp.customize('layout_type').get() === 'boxed') {
                $('.site').css('max-width', newval + 'px');
            }
        });
    });

    // Video Grid Width
    wp.customize('video_grid_width', function(value) {
        value.bind(function(newval) {
            $('.videos').css('grid-template-columns', `repeat(auto-fill, minmax(${newval}px, 1fr))`);
        });
    });

    /**
     * Adjust brightness of a hex color
     */
    function adjustBrightness(hex, steps) {
        hex = hex.replace('#', '');
        let r = parseInt(hex.substr(0, 2), 16);
        let g = parseInt(hex.substr(2, 2), 16);
        let b = parseInt(hex.substr(4, 2), 16);

        r = Math.max(0, Math.min(255, r + steps));
        g = Math.max(0, Math.min(255, g + steps));
        b = Math.max(0, Math.min(255, b + steps));

        const rHex = ('0' + r.toString(16)).slice(-2);
        const gHex = ('0' + g.toString(16)).slice(-2);
        const bHex = ('0' + b.toString(16)).slice(-2);

        return '#' + rHex + gHex + bHex;
    }
})(jQuery);