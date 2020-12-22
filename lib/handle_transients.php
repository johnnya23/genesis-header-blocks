<?php

function jma_ghb_clear_transients_save_post($post_id)
{
    global $wpdb;

    /*$plugin_options = $wpdb->get_results("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_jma_ghb_component%forpost{$post_id}' OR option_name LIKE '_transient_jma_ghb_component{$post_id}%'");*/

    $plugin_options = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_jma_ghb_component%'");

    foreach ($plugin_options as $option) {
        delete_option($option->option_name);
    }
}
add_action('save_post', 'jma_ghb_clear_transients_save_post');
