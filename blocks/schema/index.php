<?php

/**
 * Plugin Name: schema block
 * Plugin URI: https://github.com/WordPress/gutenberg-examples
 * Description: schema wrapped contact info for footer.
 * Version: 1.1.0
 * Author: john
 *
 * @package gutenberg-examples
 */

defined('ABSPATH') || exit;

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.  'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'
 */
function JMA_GHB_schema_block()
{
    if (! function_exists('register_block_type')) {
        // Gutenberg is not active.
        return;
    }
    $min = WP_DEBUG? '': '.min';
    wp_register_script(
        'jma-ghb-schema-block-script',
        plugins_url('block.js', __FILE__),
        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
        filemtime(plugin_dir_path(__FILE__) . 'block' . $min . '.js')
    );

    register_block_type('jma-ghb/schema-block', array(
        'editor_style' => 'jma-ghb-style',
        'editor_script' => 'jma-ghb-schema-block-script'
    ));
}
add_action('init', 'JMA_GHB_schema_block');
