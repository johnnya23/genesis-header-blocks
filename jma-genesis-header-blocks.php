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
    foreach (glob(JMA_GHB_BASE_DIRECTORY . 'blocks/*/index.php') as $file) {
        include $file;
    }
}
add_action('after_setup_theme', 'JMA_GHB_after_setup_theme');

$headers = new JMA_GHB_CPT('header');
$footers = new JMA_GHB_CPT('footer');

function jma_ghb_enqueue_scripts()
{
    $plugins_url = plugins_url('/');
    //globalize the main uagb stylesheet (couldn't just enqueue 'uagb-block-css' for some reason )
    wp_enqueue_style('jma_ghb_uagb-block-css', $plugins_url . 'ultimate-addons-for-gutenberg/dist/blocks.style.css');
    //if the plugin tries to re-enqueue we block
    wp_dequeue_style('uagb-block-css');


    //block specific styles
    $css = '@media(min-width:768px){.site-container .navbar .jma-positioned.jma-right >ul {float:right}.site-container .navbar .jma-positioned.jma-left >ul {float:left}.site-container .navbar .jma-positioned.jma-center > ul {text-align:center;float:none;font-size:0}.site-container .navbar .jma-positioned.jma-center > ul ul {text-align:left;min-width:200px}.site-container .navbar .jma-positioned.jma-center >ul > li {display:inline-block;float:none}}';

    $mods = jma_gbs_get_theme_mods();
    $footer_post_id = jma_ghb_get_header_footer($mods, 'footer');
    $header_post_id = jma_ghb_get_header_footer($mods, 'header');

    $ids = array($header_post_id, $footer_post_id);

    foreach ($ids as $id) {
        //$post is the post object for the header and footer custom posts
        // that hold the header and footer content.
        $post = get_post($id);

        if (function_exists('has_blocks') && has_blocks($post->post_content)) {
            $blocks = parse_blocks($post->post_content);

            if (is_array($blocks)) {
                //modified version of the main plugins's UAGB_Helper::get_stylesheet method
                $css .= jma_ghb_get_stylesheet($blocks);
            }
        }
    }
    wp_add_inline_style('jma_ghb_uagb-block-css', $css);
}

//
function jma_ghb_template_redirect()
{
    add_action('wp_enqueue_scripts', 'jma_ghb_enqueue_scripts', 999);
}
add_action('template_redirect', 'jma_ghb_template_redirect', 999);



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
    if (function_exists('jma_gbs_get_theme_mods')) {
        $mods = jma_gbs_get_theme_mods();
    }
    if (isset($mods['jma_ghb_home_header_post']) && $mods['jma_ghb_home_header_post']) {
        $header_post_id = jma_ghb_get_header_footer($mods, 'header');
        echo apply_filters('the_content', get_the_content(null, false, $header_post_id));
    } else {
        echo 'create and set a header';
    }
}

function JMA_GHB_do_footer()
{
    if (function_exists('jma_gbs_get_theme_mods')) {
        $mods = jma_gbs_get_theme_mods();
    }
    if (isset($mods['jma_ghb_home_footer_post']) && $mods['jma_ghb_home_footer_post']) {
        $footer_post_id = jma_ghb_get_header_footer($mods, 'footer');
        echo apply_filters('the_content', get_the_content(null, false, $footer_post_id));
    } else {
        echo 'create and set a footer';
    }
}
