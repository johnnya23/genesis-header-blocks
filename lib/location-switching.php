<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

function jma_ghb_get_component($pos = 'header', $post = 0)
{
    if (!$post) {
        global $wp_query;
        $id = $wp_query->queried_object_id;
    } else {
        if (is_object($post)) {
            $id = $post->ID;
        } elseif (is_int($post)) {
            $id = $post;
        }
    }
    if (isset($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy) {
        //if its a term page we prefix with taxonomy to avoid id duplicates
        $id = $wp_query->queried_object->taxonomy . $id;
    }

    if (function_exists('jma_gbs_get_theme_mods')) {
        $mods = jma_gbs_get_theme_mods();
    }
    $target_post_for_page = 0;
    $return = array('this_id' => $id, 'target' => false, 'page_options' => array('slider_id' => 0));
    $page_options = array();
    //get a

    if (is_singular() || is_home()) {
        if ((null !== get_post_meta($id, '_jma_ghb_header_footer_key', true)) && get_post_meta($id, '_jma_ghb_header_footer_key', true)) {
            $page_options = get_post_meta($id, '_jma_ghb_header_footer_key', true);
            $return['page_options'] = $page_options;
            if (isset($page_options[$pos . '_id'])) {
                $target_post_for_page = $page_options[$pos . '_id'];
            }
        }
    } elseif (is_archive()) {
        if ((null !== get_term_meta($id, $pos . '_val', true)) && get_term_meta($id, $pos . '_val', true)) {
            $target_post_for_page = get_term_meta($id, $pos . '_val', true);
        }
    }

    //if we have a value here we are DONE
    if ($target_post_for_page) {
        $return['target'] = $target_post_for_page;
    } else {
        //if not we start looking for a theme option
        //first we drop in the fallback
        if (isset($mods['jma_ghb_fallback_' . $pos . '_post']) && $mods['jma_ghb_fallback_' . $pos . '_post']) {
            $return['target'] = $mods['jma_ghb_fallback_' . $pos . '_post'];
        }
        if (is_front_page()) {
            if (isset($mods['jma_ghb_home_' . $pos . '_post']) && $mods['jma_ghb_home_' . $pos . '_post']) {
                $return['target'] = $mods['jma_ghb_home_' . $pos . '_post'];
            }
        } elseif (is_singular()) {
            //for pages posts cpts
            $type = get_post_type();
            if (isset($mods['jma_ghb_' . $type . '_' . $pos . '_post']) && $mods['jma_ghb_' . $type . '_' . $pos . '_post']) {
                $return['target'] = $mods['jma_ghb_' . $type . '_' . $pos . '_post'];
            }
        } elseif (is_archive() || is_search()) {
            //only one theme posibilty Here
            if (isset($mods['jma_ghb_archive_' . $pos . '_post']) && $mods['jma_ghb_archive_' . $pos . '_post']) {
                $return['target'] = $mods['jma_ghb_archive_' . $pos . '_post'];
            }
        }
    }
    return apply_filters('jma_ghb_get_component_filter', $return, $wp_query) ;
}
