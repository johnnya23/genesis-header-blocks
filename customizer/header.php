<?php

/**
* jma_gbs_settings_process creates customizer settings
*
*
* @param string $cpt post type slug
* @return $control  control members of $wp_customize
* @return array $return post id and title pairs as array
*/

$default_headers = jma_gbh_get_header_footer('header');
$headers = jma_gbh_get_header_footer('header', false);

//jma_gbh_get_header_footer($loc, $add_default = true)

return array(
        'home_header_post' => array(
            'default' => 0,
            'label' => __('Default Post for Frontpage', 'jma_gbs'),
            'description' => esc_html__('Post that will provide header content your homepage.'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $headers
        ),
        'page_header_post' => array(
            'default' => 0,
            'label' => __('Default Post for Pages', 'jma_gbs'),
            'description' => esc_html__('Post that will provide header content for pages (leave as "default" to use same header as home).'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $default_headers
        ),
        'single_header_post' => array(
            'default' => 0,
            'label' => __('Default Post for Header on Blog Posts', 'jma_gbs'),
            'description' => esc_html__('Post that will provide header content on individual blog posts (leave as "default" to use same header as home).'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $default_headers
        ),
        'archive_header_post' => array(
            'default' => 0,
            'label' => __('Default Post for Header on Archive Pages', 'jma_gbs'),
            'description' => esc_html__('Post that will provide header content on archive pages (leave as "default" to use same header as home).'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $default_headers
        ),
        'fallback_header_post' => array(
            'default' => 0,
            'label' => __('Default Post for Header on Other Pages', 'jma_gbs'),
            'description' => esc_html__('Post that will provide header content on search results author pages... (leave as "default" to use same header as home).'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $default_headers
        ),
);
