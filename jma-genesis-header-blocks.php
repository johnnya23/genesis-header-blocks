<?php
/**
*Plugin Name: Genesis Header Blocks
*Description: allows blocks for header and footer areas of Genesis Theme
*Version: 1.0
*Author: John Antonacci
*Author URI: https://cleansupersites.com
*License: GPL2
 */

 /**
  * Absolute file path to Genesis Bootstrap base directory.
  */
define('JMA_GHB_BASE_DIRECTORY', plugin_dir_path(__FILE__));

 /**
  * URI to Genesis Bootstrap base directory.
  */
define('JMA_GHB_BASE_URI', plugin_dir_url(__FILE__));

require_once JMA_GHB_BASE_DIRECTORY . 'block-helpers.php';
spl_autoload_register('jma_ghb_autoloader');
function jma_ghb_autoloader($class_name)
{
    if (false !== strpos($class_name, 'JMA_GHB')) {
        $classes_dir = JMA_GHB_BASE_DIRECTORY. DIRECTORY_SEPARATOR . 'classes';
        $class_file = $class_name . '.php';
        require_once $classes_dir . DIRECTORY_SEPARATOR . $class_file;
    }
}
$headers = new JMA_GHB_CPT('header');
$footers = new JMA_GHB_CPT('footer');

function JMA_GHB_after_setup_theme()
{
    require_once JMA_GHB_BASE_DIRECTORY . 'blocks/menu/index.php';
    require_once JMA_GHB_BASE_DIRECTORY . 'blocks/logo2/index.php';
}
add_action('after_setup_theme', 'JMA_GHB_after_setup_theme');

function jma_ghb_enqueue_scripts()
{
    $site_url = site_url('/');
    //globalize the main uagb stylesheet (couldn't just enqueue 'uagb-block-css' for some reason )
    wp_enqueue_style('jma_ghb_uagb-block-css', $site_url . 'wp-content/plugins/ultimate-addons-for-gutenberg/dist/blocks.style.css');
    //if the plugin tries to re-enqueue we block
    wp_dequeue_style('uagb-block-css');


    //block specific styles
    $return = '@media(min-width:768px){.site-container .navbar .jma-positioned.jma-right >ul {float:right}.site-container .navbar .jma-positioned.jma-left >ul {float:left}.site-container .navbar .jma-positioned.jma-center > ul {text-align:center;float:none;font-size:0}.site-container .navbar .jma-positioned.jma-center > ul ul {text-align:left;min-width:200px}.site-container .navbar .jma-positioned.jma-center >ul > li {display:inline-block;float:none}}';

    $locations = array('header', 'footer');

    foreach ($locations as $location) {
        //$post is the post object for the header and footer custom posts
        // that hold the header and footer content.
        $post = get_post(get_theme_mod('jma_ghb_' . $location . '_post'));

        if (function_exists('has_blocks') && has_blocks($post->post_content)) {
            $blocks = parse_blocks($post->post_content);

            if (is_array($blocks)) {
                //modified version of the main plugins's UAGB_Helper::get_stylesheet method
                $return .= jma_ghb_get_stylesheet($blocks);
            }
        }
    }
    wp_add_inline_style('jma_ghb_uagb-block-css', $return);
}

//jma_ghb_get_scripts
function jma_ghb_template_redirect()
{
    add_action('wp_enqueue_scripts', 'jma_ghb_enqueue_scripts', 999);
}
add_action('template_redirect', 'jma_ghb_template_redirect', 999);










function jma_ghb_customizer_control($wp_customize)
{/*
    $wp_customize->add_section('jma_ghb_header_controls_section', array(
    'panel' => 'jma_ghb_header_controls_panel',
        'title'      => __('Page', 'jma_gbs'),
        'priority'   => 30,
    ));
    $wp_customize->add_panel('jma_ghb_header_controls_panel', array(
        'title'      => __('JMA Panel', 'jma_gbs'),
        'priority'   => 30,
    ));*/
    $items = array();
    foreach (glob(JMA_GHB_BASE_DIRECTORY . 'customizer/*.php') as $file) {
        $new = include $file;
        array_push($items, $new);
    }
    jma_gbs_settings_process($items, $wp_customize, 'jma_ghb_');
}
add_action('customize_register', 'jma_ghb_customizer_control');

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
/**
 *

 */
function JMA_GHB_unload_framework()
{
    //echo get_theme_mod('JMA_GHB_header_page'). 'dddd';

    $header_post = 0;
    if (get_theme_mod('jma_ghb_header_post')) {
        $header_post = get_theme_mod('jma_ghb_header_post');
    }
    if (defined('GENESIS_LOADED_FRAMEWORK') && $header_post != 0) {
        remove_action('genesis_after_header', 'genesis_do_subnav');
        remove_action('genesis_after_header', 'genesis_do_nav');

        remove_action('genesis_header', 'genesis_do_header');
        add_action('genesis_header', 'JMA_GHB_do_header');
        remove_all_actions('genesis_before_loop', 99);

        if (!is_single($header_post)) {
        }
    }



    $footer_post = 0;
    if (get_theme_mod('jma_ghb_footer_post')) {
        $footer_post = get_theme_mod('jma_ghb_footer_post');
    }
    if (defined('GENESIS_LOADED_FRAMEWORK') && $footer_post != 0) {
        add_action('genesis_footer', 'JMA_GHB_do_footer');
    }
}
add_action('template_redirect', 'JMA_GHB_unload_framework', 99);

function JMA_GHB_do_header()
{
    echo apply_filters('the_content', get_the_content(null, false, get_theme_mod('jma_ghb_header_post')));
}

function JMA_GHB_do_footer()
{
    echo apply_filters('the_content', get_the_content(null, false, get_theme_mod('jma_ghb_footer_post')));
}
