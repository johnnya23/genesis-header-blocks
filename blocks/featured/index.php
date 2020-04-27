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
 function JMA_GHB_featured()
 {
     if (! function_exists('register_block_type')) {
         // Gutenberg is not active.
         return;
     }
     // Scripts.
     wp_register_script(
        'jma-ghb-featured-script', // Handle.
        plugins_url('block.js', __FILE__), // Block.js: We register the block here.
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . 'block.js'),
        true
    );

     wp_register_style(
        'jma-ghb-featured-style',
        plugins_url('block.css', __FILE__),
        array(  ),
        filemtime(plugin_dir_path(__FILE__) . 'block.css')
    );

     // Here we actually register the block with WP, again using our namespacing.
     // We also specify the editor script to be used in the Gutenberg interface.
     register_block_type('jma-ghb/featued-block', array(
         'attributes'      => array(
             'mediaID' => array(
                 'type' => 'integer',
             ),
             'mediaURL' => array(
                 'type' => 'string',
             ),
             'display_width' => array(
                 'type' => 'string',
             ),
             'display_height' => array(
                 'type' => 'string',
             ),
             'vertical_alignment' => array(
                 'type' => 'string',
             )
     ),
        'style' => 'jma-ghb-featured-style',
        'editor_style' => 'jma-ghb-featured-style',
        'editor_script' => 'jma-ghb-featured-script',
        'render_callback' => 'JMA_GHB_featured_callback',
    ));
 }
 // Hook: Editor assets.
 add_action('init', 'JMA_GHB_featured');

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
function JMA_GHB_featured_callback($atts, $content)
{
    global $post;
    $featured_wrap_style = isset($atts['display_width']) && $atts['display_width']? ' style="width: 100%;max-width:100%"': '';
    $im = get_the_post_thumbnail($post, 'full');
    $position_content_style = '';
    if (isset($atts['vertical_alignment']) && $atts['vertical_alignment']) {
        if ($atts['vertical_alignment'] == 1) {
            $position_content_style = ' style="align-self:flex-start"';
        }
        if ($atts['vertical_alignment'] == 2) {
            $position_content_style = ' style="align-self:flex-end"';
        }
    }
    if (isset($atts['mediaID']) && $atts['mediaID']) {
        $im = wp_get_attachment_image($atts['mediaID'], 'full', false, array('style' => "height:{$atts["display_height"]}"));
    }
    $x = '<div class="jma-ghb-featured-wrap"' . $featured_wrap_style . '>';
    $x .= '<div class="inner-visual">';
    $x .= $im;
    $x .= '</div>';
    $x .= '<div class="inner-content">';
    $x .= '<div class="position-content"' . $position_content_style . '>';
    $x .= $content;
    $x .= '</div>';
    $x .= '</div>';
    $x .= '</div>';
    ob_start();
    echo $x;
    return ob_get_clean();
}
