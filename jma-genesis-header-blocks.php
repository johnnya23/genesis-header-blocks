<?php
/**
*Plugin Name: Genesis Header Blocks
*Description: allows blocks for header and footer areas of Genesis Theme
*Version: 1.0
*Author: John Antonacci
*Author URI: https://cleansupersites.com
*License: GPL2
 */

 function JMA_GHB_admin_notice()
 {
     echo '<div class="notice notice-error is-dismissible">
             <p>The Genesis Header Blocks plugin REQUIRES <a href="https://wordpress.org/plugins/ultimate-addons-for-gutenberg/" target="_blank">Ultimate Addons for Gutenberg</a> plugin AND Bootstrap Genesis Plugin</p>
         </div>';
 }
function JMA_GHB_check_for_plugin()
{
    if (!is_plugin_active('ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php') || !is_plugin_active('jma-bootstrap-genesis/jma-bootstrap-genesis.php')) {
        add_action('admin_notices', 'JMA_GHB_admin_notice');
        return null;
    }
}
add_action('admin_init', 'JMA_GHB_check_for_plugin');

 /**
  * Absolute file path to Genesis Bootstrap base directory.
  */
define('JMA_GHB_BASE_DIRECTORY', plugin_dir_path(__FILE__));

 /**
  * URI to Genesis Bootstrap base directory.
  */
define('JMA_GHB_BASE_URI', plugin_dir_url(__FILE__));

if (! isset($content_width)) {
    $content_width = get_theme_mod('jma_gbs_site_width');
}

function JMA_GHB_load_files()
{
    $folders = array('lib');

    foreach ($folders as $key => $folder) {
        foreach (glob(JMA_GHB_BASE_DIRECTORY . $folder . '/*.php') as $file) {
            include $file;
        }
    }
}
add_action('genesis_setup', 'JMA_GHB_load_files', 16);


spl_autoload_register('jma_ghb_autoloader');
function jma_ghb_autoloader($class_name)
{
    if (false !== strpos($class_name, 'JMA_GHB')) {
        $classes_dir = JMA_GHB_BASE_DIRECTORY. DIRECTORY_SEPARATOR . 'classes';
        $class_file = $class_name . '.php';
        require_once $classes_dir . DIRECTORY_SEPARATOR . $class_file;
    }
}

function JMA_GHB_after_setup_theme()
{
    function theme_name_setup()
    {
        //add_theme_support('align-wide');
    }
    foreach (glob(JMA_GHB_BASE_DIRECTORY . 'blocks/*/index.php') as $file) {
        include $file;
    }
}
add_action('after_setup_theme', 'JMA_GHB_after_setup_theme');

$headers = new JMA_GHB_CPT('header');
$footers = new JMA_GHB_CPT('footer');

function jma_ghb_customizer_control($wp_customize)
{
    $items = array();
    foreach (glob(JMA_GHB_BASE_DIRECTORY . 'customizer/*.php') as $file) {
        $new = include $file;
        array_push($items, $new);
    }
    if (function_exists('jma_gbs_settings_process')) {
        jma_gbs_settings_process($items, $wp_customize, 'jma_ghb_');
    }
}
add_action('customize_register', 'jma_ghb_customizer_control');



/**
 *

 */
function JMA_GHB_unload_framework()
{
    if (defined('GENESIS_LOADED_FRAMEWORK')) {
        add_filter('body_class', 'jma_ghb_body_filter');
        add_filter('jma_ghb_features_image', 'jma_ghb_im_filter', 10, 2);
        remove_action('genesis_after_header', 'genesis_do_subnav');
        remove_action('genesis_after_header', 'genesis_do_nav');

        remove_action('genesis_header', 'genesis_do_header');
        add_action('genesis_header', 'JMA_GHB_do_header');
        remove_all_actions('genesis_before_loop', 99);

        add_action('genesis_footer', 'JMA_GHB_do_footer');
    }
}
add_action('template_redirect', 'JMA_GHB_unload_framework', 99);

function JMA_GHB_do_header()
{
    $header_post_id = jma_ghb_get_header_footer('header');
    if ($header_post_id) {
        echo apply_filters('the_content', get_the_content(null, false, $header_post_id));
    } else {
        echo 'create and set a header';
    }
}

function JMA_GHB_do_footer()
{
    $footer_post_id = jma_ghb_get_header_footer('footer');
    if ($footer_post_id) {
        echo apply_filters('the_content', get_the_content(null, false, $footer_post_id));
    } else {
        echo 'create and set a footer';
    }
}

function jma_ghb_body_filter($cl)
{
    global $post;

    if (is_object($post) && get_post_meta($post->ID, '_jma_ghb_header_footer_key', true)) {
        $page_options = get_post_meta($post->ID, '_jma_ghb_header_footer_key', true);
    }


    if (isset($page_options['sticky-header']) && $page_options['sticky-header']) {
        $cl[] = 'sticky';
    }
    return $cl;
}

function jma_ghb_im_filter($im, $page_options)
{
    if (isset($page_options['widget_area']) && $page_options['widget_area']) {
        $im .= '<div class="header-page-widget-wrap"><div class="header-page-widget">' . $page_options['widget_area'] . '</div></div>';
    }
    return $im;
}
