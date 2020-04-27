<?php

function jma_ghb_get_cpt_items($cpt)
{
    $return = array();
    $query = new WP_Query(array( 'post_type' => $cpt ));
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $return[get_the_id()] = get_the_title();
        }
    }
    return $return;
}

function jma_gbh_get_header_footer($loc, $add_default = true)
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
