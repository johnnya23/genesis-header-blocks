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
     // Scripts.
     wp_register_script(
        'jma-ghb-menu-block-script', // Handle.
        plugins_url('block.js', __FILE__), // Block.js: We register the block here.
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ), // Dependencies, defined above.
        filemtime(plugin_dir_path(__FILE__) . 'block.js'),
        true
    );
     wp_register_style('jma-ghb-featured-back-style', JMA_GHB_BASE_URI .'style.css');
     wp_register_style(
         'JMA_ghb_superfish_css',
         plugins_url('/jma-bootstrap-genesis/dist/css/superfish.min.css')
     );

     // Here we actually register the block with WP, again using our namespacing.
     // We also specify the editor script to be used in the Gutenberg interface.
     register_block_type('jma-ghb/menu-block', array(
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
        'editor_script' => array('jma-ghb-menu-block-script'),
        'editor_style' => array('JMA_ghb_superfish_css', 'jma-ghb-featured-back-style'),
        'render_callback' => 'JMA_GHB_menu',
    ));
 } // End function JMA_GHB_block().

 // Hook: Editor assets.
 add_action('init', 'JMA_GHB_menu_block');

function JMA_GHB_menu($input)
{
    ob_start();
    $align = 'left';
    if (isset($input['align'])) {
        $align = $input['align'];
    }
    if (isset($input['nav_val'])) {//genesis_do_subnav();
        if ($input['nav_val'] == 'primary') {
            add_filter(
                'JMA_GBS_nav_menu_markup_filter_inner',
                function ($content) use ($align) {
                    return str_replace('<div class="outer">', '<div class="jma-ul-wrap jma-positioned jma-' . $align . '">', $content);
                }
            );
            genesis_do_nav();
        } else {
            genesis_do_subnav();
        }
    }
    return ob_get_clean();
}
