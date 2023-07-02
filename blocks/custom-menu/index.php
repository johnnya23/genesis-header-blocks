<?php

/**
 * Block PHP code goes here.
 */

// Register the blocks

function menu_block_register_block()
{
    wp_enqueue_script(
        'jma-menu-block-script',
        plugin_dir_url(__FILE__) . 'block.js',
        array('wp-blocks', 'wp-data', 'wp-element', 'wp-components', 'wp-api-fetch', 'wp-i18n', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . 'block.js'),
        true
    );

    wp_enqueue_style(
        'jma-menu-block-style',
        plugin_dir_url(__FILE__) . 'block.css'
    );

    register_block_type(
        'jma-menu-block/menu-block',
        array(
            'attributes'      => array(
                'selectedMenu' => array(
                    'type' => 'string',
                )
            ),
            'editor_script' => 'jma-menu-block-script',
            'editor_style' => 'jma-menu-block-style',
            'render_callback' => 'jma_menu_block_render_block',
        )
    );
}
add_action('init', 'menu_block_register_block');

// Render callback function
function jma_menu_block_render_block($attributes)
{
    // Retrieve the selected menu ID from the block attributes
    $selectedMenu = isset($attributes['selectedMenu']) ? $attributes['selectedMenu'] : '';

    // Output the menu HTML code
    if ($selectedMenu) {
        $menu = wp_nav_menu(array(
            'menu' => $selectedMenu,
            'echo' => false,
        ));
        return $menu;
    }

    return ''; // Return empty string if no menu is selected
}
