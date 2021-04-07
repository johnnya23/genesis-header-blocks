<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/**
 * function jma_gbs_detect_block Detect full width blocks
 * we don't have to drill down below the first level in detectin full width
 * @return boolean $return
 */
function jma_ghb_detect_block($args)
{
    if (!is_array($args)) {
        return false;
    }
    global $post;

    $defaults = array(
        'name' => '',
        'post' => $post,
        'key' => '',
        'value' => ''
    );
    $args = wp_parse_args($args, $defaults);

    if (is_int($args['post'])) {
        $args['post'] = get_post($args['post']);
    }


    if (is_object($args['post']) && function_exists('has_blocks') && has_blocks($args['post']->post_content)) {
        $blocks = parse_blocks($args['post']->post_content);

        if (is_array($blocks)) {
            foreach ($blocks as $block) {
                //recursively run reusable blocks
                if ('core/block' == $block['blockName'] && isset($block['attrs']['ref']) && $block['attrs']['ref']) {
                    $subargs = $args;
                    $subargs['post'] = $block['attrs']['ref'];
                    if (jma_ghb_detect_block($subargs)) {
                        return true;
                    }
                }
                if (!$args['name'] || $args['name'] == $block['blockName']) {
                    //if value is set for $args['key'] or $args['value'] we require a match
                    if ($args['key'] || $args['value']) {
                        if (isset($block['attrs'][$args['key']]) && $block['attrs'][$args['key']] == $args['value']) {
                            return true;
                        }
                    } elseif ($args['name'] && $args['name'] == $block['blockName']) {
                        // else matching the block name is good enough
                        return true;
                    }
                }
            }
        }
    }
    return false;
}
