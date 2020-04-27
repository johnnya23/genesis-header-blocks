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

    // Styles.
    wp_register_style(
        'organic-profile-block-editor-style', // Handle.
        plugins_url('editor.css', __FILE__), // Block editor CSS.
        array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
        filemtime(plugin_dir_path(__FILE__) . 'editor.css')
    );
    wp_register_style(
        'organic-profile-block-frontend-style', // Handle.
        plugins_url('style.css', __FILE__), // Block editor CSS.
        array(), // Dependency to include the CSS after it.
        filemtime(plugin_dir_path(__FILE__) . 'style.css')
    );
    wp_enqueue_style(
        'organic-profile-block-fontawesome', // Handle.
        plugins_url('font-awesome.css', __FILE__), // Font Awesome for social media icons.
        array(),
        '4.7.0'
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
    $inside = $outside_close = $styles = '';
    $name = get_bloginfo('name');
    if (isset($input['content_type'])) {
        //handle main content
        if ($input['content_type'] == 3) {
            $inside = wp_get_attachment_image($input['mediaID'], 'full', false, array('title' => $name, 'alt' => get_bloginfo('description')));
        } elseif ($input['content_type'] == 0) {
            $inside = $input['custom_headline'];
        } else {
            $inside = $name;
        }
        //handle the subtitle
        if ($input['content_type'] == 1) {
            $outside_close = get_bloginfo('description');
        } elseif ($input['content_type'] == 0) {
            $outside_close = $input['custom_sub'];
        }
    }
    //handle alignment
    if (isset($input['align'])) {
        $op = $input['align'] == 'right'?'left': 'right';
        if ($input['align'] == 'right' || $input['align'] == 'left') {
            $styles = 'style="float:' . $input['align'] . ';margin-' . $op . ':5px"';
        } elseif ($input['align'] == 'center') {
            $styles = 'style="margin: 0 auto"';
        }
    }

    if ($outside_close) {
        $outside_close = '<div>' . $outside_close . '</div>';
    }

    // Determine which wrapping tags to use.
    $wrap = $input['content_type'] == '3' ? 'div' : 'h1';

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
           'open'    => sprintf("<div {$styles} class='jma-logo-wrap'><{$wrap} %s>", genesis_attr('site-title')),
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
