<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

function jma_ghb_kill_all_loc_trns_transients($x = array())
{
    global $wpdb;
    $wpdb->query("DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_jma_ghb_loc_trns%')");
}
add_action('customize_save_after', 'jma_ghb_kill_all_loc_trns_transients');


function jma_ghb_save_post_callback($post_id, $tt_id = 0, $tax = '')
{
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id)) {
        return;
    }
    $type = !$tax?get_post_type($post_id): 'category';//this puts terms into the last else Statement
    $post_id = $tax . $post_id;//term ids can duplicate post ids so we prefix them with taxonomy

    global $wpdb;
    if ($type == 'header' || $type == 'footer') {
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE ('_transient_jma_ghb_loc_trns$type$post_id%')");
    } elseif ($type == 'wp_block') {//for reusable blocks
        jma_ghb_kill_all_loc_trns_transients();
    } else {//for pages, posts and terms
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE ('_transient_jma_ghb_loc_trns%for$post_id')");
    }
}//pre_post_update
add_action('save_post', 'jma_ghb_save_post_callback');
add_action('saved_term', 'jma_ghb_save_post_callback', 10, 3);
