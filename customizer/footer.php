<?php

/**
* jma_gbs_settings_process creates customizer settings
*
*
* @param string $cpt post type slug
* @return $control  control members of $wp_customize
* @return array $return post id and title pairs as array
*/



$default_footers = jma_gbh_get_header_footer('footer');
$footers = jma_gbh_get_header_footer('footer', false);

return array(
        'home_footer_post' => array(
            'default' => 0,
            'label' => __('Default Post for Frontpage', 'jma_gbs'),
            'description' => esc_html__('Post that will provide footer content your homepage.'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $footers
        ),
        'page_footer_post' => array(
            'default' => 0,
            'label' => __('Default Post for Pages', 'jma_gbs'),
            'description' => esc_html__('Post that will provide footer content for pages (leave as "default" to use same footer as home).'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $default_footers
        ),
        'single_footer_post' => array(
            'default' => 0,
            'label' => __('Default Post for footer on Blog Posts', 'jma_gbs'),
            'description' => esc_html__('Post that will provide footer content on individual blog posts (leave as "default" to use same footer as home).'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $default_footers
        ),
        'archive_footer_post' => array(
            'default' => 0,
            'label' => __('Default Post for footer on Archive Pages', 'jma_gbs'),
            'description' => esc_html__('Post that will provide footer content on archive pages (leave as "default" to use same footer as home).'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $default_footers
        ),
        'fallback_footer_post' => array(
            'default' => 0,
            'label' => __('Default Post for footer on Other Pages', 'jma_gbs'),
            'description' => esc_html__('Post that will provide footer content on search results author pages... (leave as "default" to use same footer as home).'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $default_footers
        ),
);
