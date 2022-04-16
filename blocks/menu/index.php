<?php
/**
 * BLOCK: Genesis Menu
 *
 * Gutenberg Genesis Menu Block
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
 function JMA_GHB_menu_block()
 {
     if (! function_exists('register_block_type')) {
         // Gutenberg is not active.
         return;
     }
     $min = WP_DEBUG? '': '.min';
     // Scripts.
     wp_register_script(
         'jma-ghb-menu-block-script', // Handle.
        plugins_url('block.js', __FILE__), // Block.js: We register the block here.
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . 'block' . $min . '.js'),
         true
     );

     // Here we actually register the block with WP, again using our namespacing.
     // We also specify the editor script to be used in the Gutenberg interface.
     register_block_type(
         'jma-ghb/menu-block',
         array(
            'attributes'      => array(
                'nav_val' => array(
                    'type' => 'string',
                ),
                'align' => array(
                    'type' => 'string',
                ),
                'className' => array(
                    'type' => 'string',
                )
            ),
            'editor_script' => 'jma-ghb-menu-block-script',
            'editor_style' => 'jma-ghb-style',
            'render_callback' => 'JMA_GHB_menu',
        )
     );
 } // End function JMA_GHB_block().

 // Hook: Editor assets.
 add_action('init', 'JMA_GHB_menu_block');

function JMA_GHB_menu($input)
{
    $align = 'jma-left';
    $x = '';
    if (isset($input['align'])) {
        $align = 'jma-' . $input['align'];
    }
    if (isset($input['nav_val'])) {
        $className = isset($atts['className']) && $atts['className']? ' ' . $atts['className']: '';
        $x = genesis_get_nav_menu(array(
            'container' => 'div',
            'container_class' => 'outer ' . $align . $className,
            'theme_location' => $input['nav_val'],
            'menu_class' => 'nav sf-menu sf-arrows jma-positioned jma-' . $input['nav_val'] . '-menu'));
    }
    return $x;
}
