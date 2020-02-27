<?php

/**
* jma_gbs_settings_process creates customizer settings
*
*
* @param string $cpt post type slug
* @return $control  control members of $wp_customize
* @return array $return post id and title pairs as array
*/

$headers = array('0' => 'select');
$headers = array_replace($headers, jma_ghb_get_cpt_items('header'));

return array(
        'header_post' => array(
            'default' => 0,
            'label' => __('Post for Header', 'jma_gbs'),
            'description' => esc_html__('Page that will provide header contentf.'),
            'section' => 'genesis_header',
            'type' => 'select',
            'choices' => $headers
        )
);
