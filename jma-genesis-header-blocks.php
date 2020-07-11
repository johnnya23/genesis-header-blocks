<?php
/**
*Plugin Name: Genesis Header Blocks
*Description: allows blocks for header and footer areas of Genesis Theme
*Version: 1.0
*Author: John Antonacci
*Author URI: https://cleansupersites.com
*License: GPL2
 */
function jma_ghb_is_mobile()
{
    $useragent=$_SERVER['HTTP_USER_AGENT'];

    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
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

        remove_action('genesis_footer', 'genesis_do_footer');
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
