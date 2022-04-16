<?php
/**
 * BLOCK: Profile
 *
 * Gutenberg Featured image with custom contents
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
     $min = WP_DEBUG? '': '.min';
     // Scripts.
     wp_register_script(
         'jma-ghb-featured-script', // Handle.
        plugins_url('block.js', __FILE__), // Block.js: We register the block here.
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . 'block' . $min . '.js'),
         true
     );



     // Here we actually register the block with WP, again using our namespacing.
     // We also specify the editor script to be used in the Gutenberg interface.
     register_block_type('jma-ghb/featued-block', array(
         'attributes'      => array(
             'className' => array(
                 'type' => 'string',
             ),
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
             ),
             'scrolling' => array(
                 'type' => 'string',
             ),
             'use_featured' => array(
                 'type' => 'string',
             ),
             'allow_connection' => array(
                 'type' => 'string',
             )
     ),
        'editor_style' => 'jma-ghb-style',
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
    global $content_width;
    ob_start();
    $position_content_style = $img_ele = $visual_comps = $height = '';

    $featured_wrap_class = isset($atts['display_width']) && $atts['display_width']? ' full-width-featured': '';
    $featured_wrap_class .= isset($atts['className']) && $atts['className']? ' ' . $atts['className']: '';

    //construct the height style

    if (isset($atts["display_height"]) && $atts["display_height"]) {
        $display_height = wp_filter_nohtml_kses($atts["display_height"]);
        if ((strpos($atts["display_height"], 'calc') !== false)) {
            //it has calc so we need prefixes
            $pres = array('-webkit-', '-moz-');
            if (isset($atts["display_height_fallback"]) && $atts["display_height_fallback"]) {
                $height = 'height:' . wp_filter_nohtml_kses($atts["display_height_fallback"]) . ';';
            }
            foreach ($pres as $pre) {
                $height .= 'height:' . $pre . $display_height . ';';
            }
            $height .= 'height:' . $display_height . ';';
        } else {
            //a simple height
            $height = "height:{$display_height}";
        }
    }
    $height = apply_filters('jma_ghb_features_image_style', $height);


    $image_style_array = array();
    $content_style_array = array();
    $vis_comps_class = isset($atts['scrolling']) && $atts['scrolling']? 'inner-visual anchored': 'inner-visual';
    $visual_comps_array = array('class'=> $vis_comps_class);

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
    $allow = true;

    if (isset($atts['allow_connection']) && $atts['allow_connection'] == '0') {
        $allow = false;
    }

    if ($allow) {
        $post_id = is_home()? get_option('page_for_posts'): $post->ID;
        if (get_post_meta($post_id, '_jma_ghb_header_footer_key', true)) {
            $page_vals =  get_post_meta($post_id, '_jma_ghb_header_footer_key', true);
        }
    }

    if (isset($atts['mediaID']) && $atts['mediaID']) {
        //get the image

        $img_ele = wp_get_attachment_image($atts['mediaID'], 'full', false, $image_style_array);
    }
    // || !$page_vals['slider_id'] && $atts['use_featured']

    if (is_archive()) {
        global $wp_query;
        $id = $wp_query->queried_object_id;
        if ((null !== get_term_meta($id, 'category-image-id', true)) && get_term_meta($id, 'category-image-id', true)) {
            $img_ele = wp_get_attachment_image(get_term_meta($id, 'category-image-id', true), 'full', false, $image_style_array);
        }
    } elseif (isset($atts['use_featured'])  || isset($page_vals['slider_id'])) {
        $forced = substr($page_vals['slider_id'], 0, 5) === "force";
        if ($forced) {
            if ($page_vals['slider_id'] == 'force_featured') {
                if (has_post_thumbnail($post)) {
                    $img_ele = wp_get_attachment_image(get_post_thumbnail_id($post_id), 'full', false, $image_style_array);
                }
            }
        } elseif (!$page_vals['slider_id']) {
            if (isset($atts['use_featured']) && $atts['use_featured']) {
                //this gives us the featured image
                if (has_post_thumbnail($post)) {
                    $img_ele = wp_get_attachment_image(get_post_thumbnail_id($post_id), 'full', false, $image_style_array);
                }
            }
        } elseif ($page_vals['slider_id']) {
            //this means another plugin has left a value in the form
            //so this is that access
            $img_ele = apply_filters('jma_ghb_features_slider', $img_ele, $page_vals);
        }
        //this means zero (default) was set as value so original image remains
    }

    //if there is no image, but there is a height, we apply height to the wrapper

    if ($height) {
        $visual_comps_array['style'] =  $height ;
    }
    foreach ($visual_comps_array as $attr => $value) {
        $visual_comps .= ' ' . $attr . '="' . $value . '"';
    }
    // $featured_wrap_class sets width
    $x = '<div class="jma-ghb-featured-wrap' . $featured_wrap_class . '">';
    $x .= '<div class="inner-wrap">';

    $x .= '<div class="inner-content"' . $position_content_style . '>';
    $x .= $content;
    $x .= '</div>';

    $x .= '<div' . $visual_comps . '>';
    $x .= apply_filters('jma_ghb_features_image', $img_ele, $page_vals, $atts);
    $x .= '</div>';

    $x .= '</div>';
    $x .= '</div>';
    echo $x;
    return ob_get_clean();
}
