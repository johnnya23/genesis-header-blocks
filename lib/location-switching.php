<?php

function jma_ghb_get_header_footer($mods, $pos = 'header', $post = 0)
{
    global $wp_query;

    $id = $wp_query->queried_object_id;
    $target_post_for_page = $target_post_for_cat = 0;
    //get a
    if ((null !== get_post_meta($id, '_jma_ghb_header_footer_key', true)) && get_post_meta($id, '_jma_ghb_header_footer_key', true)) {
        $page_values = get_post_meta($id, '_jma_ghb_header_footer_key', true);
        $target_post_for_page = $page_values[$pos . '_id'];
    }
    if ((null !== get_term_meta($id, $pos . '_val', true)) && get_term_meta($id, $pos . '_val', true)) {
        $target_post_for_cat = get_term_meta($id, $pos . '_val', true);
    }

    $return = $mods['jma_ghb_home_' . $pos . '_post'];
    if (is_front_page()) {
        if ($target_post_for_page) {
            $return = $target_post_for_page;
        } else {
            $return = $mods['jma_ghb_home_' . $pos . '_post'];
        }
    } elseif (is_page()) {
        if ($target_post_for_page) {
            $return = $target_post_for_page;
        } elseif (isset($mods['jma_ghb_page_' . $pos . '_post']) && $mods['jma_ghb_page_' . $pos . '_post']) {
            $return = $mods['jma_ghb_page_' . $pos . '_post'];
        }
    } elseif (is_single()) {
        if ($target_post_for_page) {
            $return = $target_post_for_page;
        } elseif (isset($mods['jma_ghb_single_' . $pos . '_post']) && $mods['jma_ghb_single_' . $pos . '_post']) {
            $return = $mods['jma_ghb_single_' . $pos . '_post'];
        }
    } elseif (is_archive()) {
        if ($target_post_for_cat) {
            $return = $target_post_for_cat;
        } elseif (isset($mods['jma_ghb_archive_' . $pos . '_post']) && $mods['jma_ghb_archive_' . $pos . '_post']) {
            $return = $mods['jma_ghb_archive_' . $pos . '_post'];
        }
    } else {
        if ($target_post_for_page) {
            $return = $target_post_for_page;
        } elseif ($target_post_for_cat) {
            $return = $target_post_for_cat;
        } elseif (isset($mods['jma_ghb_fallback_' . $pos . '_post']) && $mods['jma_ghb_fallback_' . $pos . '_post']) {
            $return = $mods['jma_ghb_fallback_' . $pos . '_post'];
        }
    }
    return $return;
}
