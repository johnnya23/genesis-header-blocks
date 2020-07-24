<?php

/**
* jma_gbs_settings_process creates customizer settings
*
*
*/


$default_footers = jma_ghb_header_footer_list('footer');
$footers = jma_ghb_header_footer_list('footer', false);

$jma_ghb_cpt = jma_ghb_get_cpt();
$post_type_array = array();
foreach ($jma_ghb_cpt as $slug => $obj) {
    $post_type_array[$slug . '_footer_post'] = array(
        'default' => 0,
        'label' => __('Default Footer for ' . $obj->label, 'jma_gbs'),
        'description' => esc_html__('Default Footer for your ' . $obj->label . '.'),
        'section' => 'genesis_footer',
        'type' => 'select',
        'choices' => $default_footers
    );
}

$special_choices = array(
        'fallback_footer_post' => array(
            'default' => 0,
            'label' => __('Default Footer', 'jma_gbs'),
            'description' => esc_html__('Footer that will provide content on search results author pages... ad anything that isn\'t set below.'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $footers
        ),
        'home_footer_post' => array(
            'default' => 0,
            'label' => __('Default Footer for Frontpage', 'jma_gbs'),
            'description' => esc_html__('Footer that will provide content your homepage.'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $default_footers
        ),
        'archive_footer_post' => array(
            'default' => 0,
            'label' => __('Default Footer for Archive Pages', 'jma_gbs'),
            'description' => esc_html__('Footer that will provide content on archive pages (leave as "default" to use same footer as home).'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $default_footers
        ),
);
return array_merge($special_choices, $post_type_array);
