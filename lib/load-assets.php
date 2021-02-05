<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function jma_ghb_enqueue_scripts()
{
    $plugins_url = plugins_url('/');
    wp_register_style('jma-ghb-featured-style', JMA_GHB_BASE_URI .'style.css');
    wp_enqueue_style('jma-ghb-featured-style');
}


function jma_ghb_template_redirect()
{
    add_action('wp_enqueue_scripts', 'jma_ghb_enqueue_scripts', 999);
}
add_action('template_redirect', 'jma_ghb_template_redirect', 15);
