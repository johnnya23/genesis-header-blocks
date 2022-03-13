<?php

/**
* jma_gbs_settings_process creates customizer settings
*
*
*/

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


$default_headers = jma_ghb_header_footer_list('header');
$headers = jma_ghb_header_footer_list('header', false);

$jma_ghb_cpt = jma_ghb_get_cpt();
$post_type_array = array();
foreach ($jma_ghb_cpt as $slug => $obj) {
    $post_type_array[$slug . '_header_post'] = array(
        'default' => 0,
        'label' => __('Default Header for ' . $obj->label, 'jma_gbs'),
        'description' => esc_html__('Default Header for your ' . $obj->label . '.'),
        'section' => 'genesis_header',
        'type' => 'select',
        'choices' => $default_headers
    );
}

$special_choices = array(
        'fallback_header_post' => array(
            'default' => 0,
            'label' => __('Default Header', 'jma_gbs'),
            'description' => esc_html__('Header that will provide content on search results author pages... ad anything that isn\'t set below.'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $headers
        ),
        'home_header_post' => array(
            'default' => 0,
            'label' => __('Default Header for Frontpage', 'jma_gbs'),
            'description' => esc_html__('Header that will provide content your homepage.'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $default_headers
        ),
        'archive_header_post' => array(
            'default' => 0,
            'label' => __('Default Header for Archive and Search Results Pages', 'jma_gbs'),
            'description' => esc_html__('Header that will provide content on archive pages (leave as "default" to use same header as home).'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $default_headers
        ),
);
return array_merge($special_choices, $post_type_array);
