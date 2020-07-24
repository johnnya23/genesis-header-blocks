<?php

function jma_ghb_get_cpt_items($cpt)
{
    $return = array();
    $args = array(
        'numberposts' => 100,
        'post_type'   => $cpt
    );

    $entries = get_posts($args);
    foreach ($entries as $entry) {
        $return[$entry->ID] = $entry->post_title;
    }
    return $return;
}

function jma_ghb_header_footer_list($loc, $add_default = true)
{
    $return = jma_ghb_get_cpt_items($loc);
    if (!count($return)) {
        return array('0' => 'you need to create a ' . $loc . ' post(s)');
    }
    if ($add_default) {
        $return = array_replace(array('0' => 'default'), $return);
    }
    return $return;
}
