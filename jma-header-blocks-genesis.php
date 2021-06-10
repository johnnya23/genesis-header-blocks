<?php
/**
*Plugin Name: JMA Genesis Header Blocks
*Description: allows blocks for header and footer areas of Genesis Theme supports getwid
*Version: 2.3
*Author: John Antonacci
*Author URI: https://cleansupersites.com
*License: GPL2
 */

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (get_option('template') != 'genesis') {
    return;
}


function JMA_GHB_admin_notice()
{
    echo '<div class="notice notice-error is-dismissible">
             <p>The Genesis Header Blocks plugin REQUIRES Bootstrap Genesis Plugin</p>
         </div>';
}
function JMA_GHB_check_for_plugin()
{
    if (!is_plugin_active('jma-bootstrap-genesis/jma-bootstrap-genesis.php')) {
        add_action('admin_notices', 'JMA_GHB_admin_notice');
        return null;
    }
}
add_action('admin_init', 'JMA_GHB_check_for_plugin');

 /**
  * Absolute file path to Genesis Bootstrap base directory.  UAGB_DIR
  */
define('JMA_GHB_BASE_DIRECTORY', plugin_dir_path(__FILE__));


 /**
  * URI to Genesis Bootstrap base directory.
  */
define('JMA_GHB_BASE_URI', plugin_dir_url(__FILE__));

if (! isset($content_width)) {
    $content_width = get_theme_mod('jma_gbs_site_width');
}

function jma_ghb_get_cpt()
{
    $custom_post_types = array();
    $builtins = array(false, true);
    $output = 'objects'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'
    foreach ($builtins as $builtin) {
        $args=array(
                'public'                => true,
                'exclude_from_search'   => false,
                '_builtin'              => $builtin
            );
        $new = get_post_types($args, $output, $operator);
        $custom_post_types = array_merge($new, $custom_post_types);
    }
    $remove = array( 'header', 'footer', 'attachment');
    foreach ($remove as $value) {
        unset($custom_post_types[$value]);
    }
    return $custom_post_types;
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


function JMA_GHB_after_setup_theme()
{
    foreach (glob(JMA_GHB_BASE_DIRECTORY . 'blocks/*/index.php') as $file) {
        include $file;
    }
}
add_action('after_setup_theme', 'JMA_GHB_after_setup_theme');

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


spl_autoload_register('jma_ghb_autoloader');
function jma_ghb_autoloader($class_name)
{
    if (false !== strpos($class_name, 'JMA_GHB')) {
        $classes_dir = JMA_GHB_BASE_DIRECTORY. DIRECTORY_SEPARATOR . 'classes';
        $class_file = $class_name . '.php';
        require_once $classes_dir . DIRECTORY_SEPARATOR . $class_file;
    }
}
$ghb_components = array('header', 'footer');
foreach ($ghb_components as $ghb_component) {
    $$ghb_component = new JMA_GHB_CPT($ghb_component);
}

function jma_ghb_is_mobile()
{
    /* don't forget to add prefix when updating the class
     * https://github.com/serbanghita/Mobile-Detect/blob/master/Mobile_Detect.php
     */

    $detect = new JMA_GHB_Mobile_Detect;
    // Basic detection.
    return $detect->isMobile();
}

function jma_ghb_body_class($cl)
{
    if (jma_ghb_is_mobile()) {
        $cl[] = 'jma-mobile';
    } else {
        $cl[] = 'jma-desktop';
    }
    return $cl;
}
add_filter('body_class', 'jma_ghb_body_class');

/**
 *

 */
function JMA_GHB_unload_framework()
{
    if (defined('GENESIS_LOADED_FRAMEWORK')) {
        add_filter('jma_ghb_features_image', 'jma_ghb_im_filter', 10, 2);
        remove_action('genesis_after_header', 'genesis_do_subnav');
        remove_action('genesis_after_header', 'genesis_do_nav');

        remove_action('genesis_header', 'genesis_do_header');
        add_action('genesis_header', 'JMA_GHB_do_header');
        remove_all_actions('genesis_before_loop', 99);

        remove_action('genesis_footer', 'genesis_do_footer');
        add_action('genesis_footer', 'JMA_GHB_do_footer');
    }
}
add_action('template_redirect', 'JMA_GHB_unload_framework', 99);

function JMA_GHB_do_header()
{
    $header_post_id = jma_ghb_get_component('header');
    if ($header_post_id) {
        global $post;
        $post_id = is_home()? get_option('page_for_posts'): $post->ID;
        $html = apply_filters('the_content', get_the_content(null, false, $header_post_id));
        echo $html;
    } else {
        echo 'create and set a header';
    }
}

function JMA_GHB_do_footer()
{
    $footer_post_id = jma_ghb_get_component('footer');
    if ($footer_post_id) {
        global $post;
        $post_id = is_home()? get_option('page_for_posts'): $post->ID;
        $html = apply_filters('the_content', get_the_content(null, false, $footer_post_id));
        echo $html;
    } else {
        echo 'create and set a footer';
    }
}

function jma_ghb_im_filter($im, $page_options)
{
    if (isset($page_options['widget_area']) && $page_options['widget_area']) {
        $im .= '<div class="header-page-widget-wrap"><div class="header-page-widget">' . $page_options['widget_area'] . '</div></div>';
    }
    return $im;
}
