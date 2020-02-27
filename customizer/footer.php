<?php

/**
* jma_gbs_settings_process creates customizer settings
*
*
* @param string $cpt post type slug
* @return $control  control members of $wp_customize
* @return array $return post id and title pairs as array
*/

$footers = array('0' => 'select');
$footers = array_replace($footers, jma_ghb_get_cpt_items('footer'));

return array(
        'footer_post' => array(
            'default' => 0,
            'label' => __('Post for Footer', 'jma_gbs'),
            'description' => esc_html__('Page that will provide footer content.'),
            'section' => 'genesis_footer',
            'type' => 'select',
            'choices' => $footers
        )
);
