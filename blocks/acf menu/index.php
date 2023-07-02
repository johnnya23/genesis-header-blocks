<?php

/**
 * BLOCK: ACF Menu
 *
 * Gutenberg ACF Menu Block
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
if (!(function_exists('register_block_type') && function_exists('register_acf_blocks'))) {
    // Gutenberg is not active.
    //return;
}

include(__DIR__ . '/acf-menu-field/acf-menu-field.php');
function register_acf_menu_block()
{
    register_block_type(__DIR__);
}
add_action('init', 'register_acf_menu_block');

function JMA_GHB_ACF_menu($input)
{
    /*echo '<pre>';
    print_r($input);
    echo '</pre>';*/
    $align = 'jma-left';
    $x = '';
    if (isset($input['align'])) {
        $align = 'jma-' . $input['align'];
    }
    if (isset($input['data']['menu'])) {
        $className = isset($input['className']) && $input['className'] ? ' ' . $input['className'] : '';
        $x = wp_nav_menu(array(
            'echo' => false,
            'container' => 'div',
            'container_class' => 'outer ' . $align . ' ' . $className,
            'menu' => $input['data']['menu'],
            'menu_class' => 'nav sf-menu sf-arrows jma-positioned jma-' . $input['data']['menu'] . '-menu',
            'link_before' => '<span itemprop="name">',
            'link_after' => '</span>'
        ));
    }
    echo $x;
}
