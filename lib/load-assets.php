<?php
function jma_ghb_enqueue_scripts()
{
    $plugins_url = plugins_url('/');
    //globalize the main uagb stylesheet (couldn't just enqueue 'uagb-block-css' for some reason )

    
    wp_enqueue_style('jma_ghb_uagb-block-css', $plugins_url . 'ultimate-addons-for-gutenberg/dist/blocks.style.css');
    //if the plugin tries to re-enqueue we block
    wp_dequeue_style('uagb-block-css');
    wp_register_style('jma-ghb-featured-style', JMA_GHB_BASE_URI .'style.css');
    wp_enqueue_style('jma-ghb-featured-style');


    //block specific styles
    $css = '';

    $footer_post_id = jma_ghb_get_header_footer('footer');
    $header_post_id = jma_ghb_get_header_footer('header');

    $ids = array($header_post_id, $footer_post_id);

    foreach ($ids as $id) {
        //$post is the post object for the header and footer custom posts
        // that hold the header and footer content.
        if ($id) {
            $post = get_post($id);

            if (function_exists('has_blocks') && has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);

                if (is_array($blocks)) {
                    //modified version of the main plugins's UAGB_Helper::get_stylesheet method
                    $css .= jma_ghb_get_stylesheet($blocks);
                }
            }
        }
    }
    wp_add_inline_style('jma_ghb_uagb-block-css', $css);
}
function jma_ghb_footer()
{
    $js = '';

    $footer_post_id = jma_ghb_get_header_footer('footer');
    $header_post_id = jma_ghb_get_header_footer('header');

    $ids = array($header_post_id, $footer_post_id);

    foreach ($ids as $id) {
        //$post is the post object for the header and footer custom posts
        // that hold the header and footer content.
        if ($id) {
            $post = get_post($id);

            if (function_exists('has_blocks') && has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);

                if (is_array($blocks)) {
                    //modified version of the main plugins's UAGB_Helper::get_script method
                    $js .= jma_ghb_get_script($blocks);
                }
            }
        }
    }
    if ($js) {
        ob_start(); ?>
    	<script type="text/javascript" id="uagb-script-frontend">document.addEventListener("DOMContentLoaded", function(){( function( $ ) { <?php echo $js; //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped?> })(jQuery)})</script>
    	<?php
        ob_end_flush();
    }
}

function jma_ghb_template_redirect()
{
    add_action('wp_enqueue_scripts', 'jma_ghb_enqueue_scripts', 999);
    add_action('wp_footer', 'jma_ghb_footer', 1000);
}
add_action('template_redirect', 'jma_ghb_template_redirect', 15);
