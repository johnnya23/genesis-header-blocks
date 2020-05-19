<?php
/**
 * BLOCK: Profile
 *
 * Gutenberg Custom Profile Block assets.
 *
 * @since   1.0.0
 * @package OPB
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
function organic_profile_block()
{
    if (! function_exists('register_block_type')) {
        // Gutenberg is not active.
        return;
    }

    // Scripts.
    wp_enqueue_script(
        'jma_ghb_menu-block-script', // Handle.
        plugins_url('block.js', __FILE__), // Block.js: We register the block here.
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . 'block.js'),
        true // Load script in footer.
    );

    // Here we actually register the block with WP, again using our namespacing.
    // We also specify the editor script to be used in the Gutenberg interface.
    register_block_type(
        'jma-ghb/logo-block',
        array(
            'attributes'      => array(
                'mediaID' => array(
                    'type' => 'integer',
                ),
                'mediaURL' => array(
                    'type' => 'string',
                ),
                'align' => array(
                    'type' => 'string',
                ),
                'content_type' => array(
                    'type' => 'string',
                ),
                'custom_headline' => array(
                    'type' => 'string',
                ),
                'custom_sub' => array(
                    'type' => 'string',
                ),
            ),
            'editor_script' => 'jma_ghb_menu-block-script',
            'render_callback' => 'JMA_GHB_logo_callback'
        )
    );
} // End function organic_profile_block().

// Hook: Editor assets.
add_action('init', 'organic_profile_block');

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
function JMA_GHB_logo_callback($input)
{

   // Set what goes inside the wrapping tags.
    global $wp_query;
    /*echo "<pre>";
    print_r($wp_query);
    echo "</pre>";*/
    $inside = $outside_close = $styles = '';
    $inside = 'Page/Post Title';
    $name = get_bloginfo('name');
    if (isset($input['content_type'])) {
        //handle main content
        if ($input['content_type'] == 4) {
            if (is_object($wp_query->queried_object)) {
                $inside = is_singular()? $wp_query->queried_object->post_title: $wp_query->queried_object->name;
            }
        } elseif ($input['content_type'] == 3) {
            $inside = wp_get_attachment_image($input['mediaID'], 'full', false, array('title' => $name, 'alt' => get_bloginfo('description')));
        } elseif ($input['content_type'] == 0) {
            $inside = $input['custom_headline'];
        } else {
            $inside = $name;
        }
        $inside = '<a href="' . get_bloginfo('url') . '">' . $inside . '</a>';
        //handle the subtitle
        if ($input['content_type'] == 1) {
            $outside_close = get_bloginfo('description');
        } elseif ($input['content_type'] == 0) {
            if (isset($input['custom_sub'])) {
                $outside_close = $input['custom_sub'];
            }
        }
    }
    
    // Determine which wrapping tags to use.
    $wrap = $input['content_type'] == '3' ? 'div' : 'h1';
    $wrap_class = $input['content_type'] == '4' ? 'entry-title' : 'jma-logo-wrap';
    $attr = $input['content_type'] != '4' ? genesis_attr('site-title') : '';

    //handle alignment
    $alignment_suffix = isset($input['align'])? $input['align']: 'left';
    $wrap_class .= ' jma-logo-' . $alignment_suffix;


    if ($outside_close) {
        $outside_close = '<div>' . $outside_close . '</div>';
    }

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
           'open'    => sprintf("<div {$styles} class='{$wrap_class}'><{$wrap} %s>", $attr),
           'close'   => "</{$wrap}>" . $outside_close . '</div>',
           'content' => $inside,
           'context' => 'site-title',
           'echo'    => false,
           'params'  => [
               'wrap' => 'h1',
           ],
       ]
   );
    ob_start();
    /*foreach ($input as $i => $item) {
        echo $i . '=>' . $item;
    }*/
    echo  $title;
    return ob_get_clean();
}
