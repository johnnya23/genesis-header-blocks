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
}


function jma_ghb_template_redirect()
{
    add_action('wp_enqueue_scripts', 'jma_ghb_enqueue_scripts', 999);
}
add_action('template_redirect', 'jma_ghb_template_redirect', 15);
