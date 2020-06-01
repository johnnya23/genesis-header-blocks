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
             'display_height_fallback' => array(
                 'type' => 'string',
             ),
             'vertical_alignment' => array(
                 'type' => 'string',
             )
     ),
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
    ob_start();
    $featured_wrap_style = $position_content_style = $im = $outerstyle = $height = '';

    $featured_wrap_style = isset($atts['display_width']) && $atts['display_width']? ' style="width: 100%;max-width:100%"': '';

    //construct the height style

    if (isset($atts["display_height"]) && $atts["display_height"]) {
        if ((strpos($atts["display_height"], 'calc') !== false)) {
            //it has calc so we need prefixes
            $pres = array('-webkit-', '-moz-');
            if (isset($atts["display_height_fallback"]) && $atts["display_height_fallback"]) {
                $height = 'height:' . $atts["display_height_fallback"] . ';';
            }
            foreach ($pres as $pre) {
                $height .= 'height:' . $pre . $atts["display_height"] . ';';
            }
            $height .= 'height:' . $atts["display_height"] . ';';
        } else {
            //a simple height
            $height = "height:{$atts["display_height"]}";
        }
    }
    $height = apply_filters('jma_ghb_features_image_style', $height);


    $image_style_array = array();
    $content_style_array = array();

    if (isset($atts['vertical_alignment']) && $atts['vertical_alignment']) {
        $content_style_array['justify-content'] = $atts['vertical_alignment'];
    }
    $content_style_array = apply_filters('jma_ghb_content_style_array', $content_style_array, $atts, $post);

    if (count($content_style_array)) {
        $position_content_style = ' style="';
        foreach ($content_style_array as $propery => $value) {
            $position_content_style .= $propery . ':' . $value . ';';
        }
        $position_content_style .=  $height . '" ';
    }


    if ($height) {
        $image_style_array = array('style' => $height);
    }

    $page_vals = array();
    if (get_post_meta($post->ID, '_jma_ghb_header_footer_key', true)) {
        $page_vals =  get_post_meta($post->ID, '_jma_ghb_header_footer_key', true);
    }
    if (isset($atts['mediaID']) && $atts['mediaID']) {
        //get the image
        if (get_post_mime_type($atts['mediaID']) == 'video/mp4') {
            $im = '<div class="bg-video">' . do_shortcode('[video preload="auto" autoplay="on" loop="on" src="' . wp_get_attachment_url($atts['mediaID']) . '"]') . '</div>';
        } else {
            $im = wp_get_attachment_image($atts['mediaID'], 'full', false, $image_style_array);
        }
    }

    if (isset($page_vals['slider_id'])) {
        if ($page_vals['slider_id'] === 'jma_featured') {
            //this gives us the featured image
            if (has_post_thumbnail($post)) {
                $im = wp_get_attachment_image(get_post_thumbnail_id($post), 'full', false, $image_style_array);
            }
        } elseif ($page_vals['slider_id']) {
            //this means another plugin has left a value in the form
            //so this is that access
            $im = apply_filters('jma_ghb_features_slider', $im, $page_vals);
        }
        //this means zero (default) was set as value so original image remains
    }

    //if there is no image, but there is a height, we apply height to the wrapper
    if ($height) {
        $outerstyle = 'style="' . $height . '"';
    }
    // $featured_wrap_style sets width
    $x = '<div class="jma-ghb-featured-wrap"' . $featured_wrap_style . '>';
    $x .= '<div class="inner-wrap">';

    $x .= '<div class="inner-content">';
    // $position_content_style sets spacing for contents and height
    $x .= '<div class="position-content"' . $position_content_style . '>';
    $x .= $content;
    $x .= '</div>';
    $x .= '</div>';

    $x .= '<div class="inner-visual" ' . $outerstyle . '>';
    $x .= $im = apply_filters('jma_ghb_features_image', $im, $page_vals);
    $x .= '</div>';

    $x .= '</div>';
    $x .= '</div>';
    echo $x;
    return ob_get_clean();
}
