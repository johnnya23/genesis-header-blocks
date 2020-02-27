<?php
/**
 * BLOCK: Profile
 *
 * Gutenberg Custom Youtube List Box
 *
 * @since   2.0
 * @package JMA
 */



 defined('ABSPATH') || exit;

 /**
  * Enqueue the block's assets for the editor.
  *
  * `wp-blocks`: Includes block type registration and related functions.
  * `wp-element`: Includes the WordPress Element abstraction for describing the structure of your blocks.
  * `wp-i18n`: To internationalize the block's text.
  *
  * @since 1.0.0
  */
 function JMA_GHB_logo_block()
 {
     if (! function_exists('register_block_type')) {
         // Gutenberg is not active.
         return;
     }
     // Scripts.
     wp_register_script(
        'jma-ghb-menu-logo-script', // Handle.
        plugins_url('block.js', __FILE__), // Block.js: We register the block here.
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . 'block.js'),
        true
    );

     // Here we actually register the block with WP, again using our namespacing.
     // We also specify the editor script to be used in the Gutenberg interface.
     register_block_type('jma-ghb/logo-block', array(
         'attributes'      => array(
             'mediaID' => array(
                 'type' => 'string',
             ),
             'mediaURL' => array(
                 'type' => 'string',
             ),
             'mediaTitle' => array(
                 'type' => 'string',
             ),
             'mediaFileName' => array(
                 'type' => 'string',
             ),
             'alignment' => array(
                 'type' => 'string',
             ),
             'content' => array(
                 'type' => 'string',
             )
         ),
        'editor_script' => 'jma-ghb-menu-logo-script',
        'render_callback' => 'JMA_GHB_seo_site_title',
    ));
 }
 // Hook: Editor assets.
 add_action('init', 'JMA_GHB_logo_block');

 /**
 * Echo the site title into the header.
 *
 * Depending on the SEO option set by the user, this will either be wrapped in an `h1` or `p` element.
 * The Site Title will be wrapped in a link to the homepage, if a custom logo is not in use.
 *
 * Applies the `genesis_seo_title` filter before echoing.
 *
 * @since 1.1.0
 */
function JMA_GHB_seo_site_title($x)
{

    // Set what goes inside the wrapping tags.
    $inside = current_theme_supports('genesis-custom-logo') && has_custom_logo() ? wp_kses_post(get_bloginfo('name')) : wp_kses_post(sprintf('<a href="%s">%s</a>', trailingslashit(home_url()), get_bloginfo('name')));

    // Determine which wrapping tags to use.
    $wrap = genesis_is_root_page() && 'title' === genesis_get_seo_option('home_h1_on') ? 'h1' : 'p';

    // Fallback for static homepage if an SEO plugin is active.
    $wrap = genesis_is_root_page() && genesis_seo_disabled() ? 'p' : $wrap;

    // Fallback for latest posts if an SEO plugin is active.
    $wrap = is_front_page() && is_home() && genesis_seo_disabled() ? 'h1' : $wrap;

    // And finally, $wrap in h1 if HTML5 & semantic headings enabled.
    $wrap = genesis_get_seo_option('semantic_headings') ? 'h1' : $wrap;

    /**
     * Site title wrapping element
     *
     * The wrapping element for the site title.
     *
     * @since 2.2.3
     *
     * @param string $wrap The wrapping element (h1, h2, p, etc.).
     */
    $wrap = apply_filters('genesis_site_title_wrap', $wrap);

    // Build the title.
    $title = genesis_markup(
        [
            'open'    => sprintf("<{$wrap} %s>", genesis_attr('site-title')),
            'close'   => "</{$wrap}>",
            'content' => $inside,
            'context' => 'site-title',
            'echo'    => false,
            'params'  => [
                'wrap' => 'h1',
            ],
        ]
    );
    $title = 'xxx';
    if (isset($x['mediaURL'])) {
        $title = $x['mediaURL'] . '<button type="button" class="components-button components-button is-button is-default is-large">open</button>';
    }
    ob_start();
    echo  $title;
    return ob_get_clean();
}
