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
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_646d3a2d8336b',
        'title' => 'ACF Menu',
        'fields' => array(
            array(
                'key' => 'field_646d3a2dc7ba0',
                'label' => 'Menu',
                'name' => 'menu',
                'aria-label' => '',
                'type' => 'menu',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'id',
                'allow_null' => 0,
            ),
            array(
                'key' => 'field_64a155067fe97',
                'label' => 'Make Primary',
                'name' => 'make_primary',
                'aria-label' => '',
                'type' => 'checkbox',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'yes' => 'Yes',
                ),
                'default_value' => array(
                    0 => 'yes',
                ),
                'return_format' => 'value',
                'allow_custom' => 0,
                'layout' => 'vertical',
                'toggle' => 0,
                'save_custom' => 0,
                'custom_choice_button_text' => 'Add new choice',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'jma-acf/acfmenu',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ));
});


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
    echo 'fff</pre>';*/
    $align = 'jma-left';
    $x = $class_string = '';
    $classes = array();
    if (isset($input['align'])) {
        $align = 'jma-' . $input['align'];
    }
    if (isset($input['data']['menu'])) {
        if (isset($input['className']) && $input['className']) $classes[] = $input['className'];
        if (isset($input['data']['make_primary'][0]) && $input['data']['make_primary'][0]) $classes[] = 'nav-primary';
        $x = wp_nav_menu(array(
            'echo' => false,
            'container' => 'div',
            'container_class' => 'outer ' . $align,
            'menu' => $input['data']['menu'],
            'menu_class' => 'nav sf-menu sf-arrows jma-positioned jma-' . $input['data']['menu'] . '-menu',
            'link_before' => '<span itemprop="name">',
            'link_after' => '</span>'
        ));
        if ($x) {
            if (count($classes)) {
                foreach ($classes as $class)
                    $class_string .= $class . ' ';
            }
            $x = '<nav class="' . $class_string . 'clearfix navbar navbar-default navbar-static-top" aria-label="Main" itemscope itemtype="https://schema.org/SiteNavigationElement">' . $x . '</nav>';
        }
    }
    echo $x;
}
