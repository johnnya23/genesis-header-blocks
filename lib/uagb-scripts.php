<?php

function jma_ghb_get_block_js($block)
{
    $block = ( array ) $block;
    if (isset($block['blockName'])) {
        $name = $block['blockName'];
    }
    $final_block  = array();
    $js = '';

    if (! isset($name)) {
        return;
    }

    if (isset($block['attrs']) && is_array($block['attrs'])) {
        $blockattr = $block['attrs'];
        if (isset($blockattr['block_id'])) {
            $block_id = $blockattr['block_id'];
        }
    }

    switch ($name) {
                case 'uagb/testimonial':
                    $js .= UAGB_Block_Helper::get_testimonial_js($blockattr, $block_id);
                    break;

                case 'uagb/blockquote':
                    $js .= UAGB_Block_Helper::get_blockquote_js($blockattr, $block_id);
                    break;

                case 'uagb/social-share':
                    $js .= UAGB_Block_Helper::get_social_share_js($blockattr, $block_id);
                    break;

                case 'uagb/table-of-contents':
                    $js .= UAGB_Block_Helper::get_table_of_contents_js($blockattr, $block_id);
                    break;

                default:
                    // Nothing to do here.
                    break;
            }

    if (isset($block['innerBlocks'])) {
        foreach ($block['innerBlocks'] as $j => $inner_block) {
            $final_block = $inner_block;
            if ('core/block' == $inner_block['blockName'] && isset($inner_block['attrs']['ref'])) {
                $id =  $inner_block['attrs']['ref'];
                $content = get_post_field('post_content', $id);

                $reusable_blocks = parse_blocks($content);
                $final_block = $reusable_blocks[0];
            }
            // Get CSS for the Block.

            $js .= jma_ghb_get_block_js($final_block);
        }
    }

    return $js;
}

function jma_ghb_get_script($blocks)
{
    $js = '';
    foreach ($blocks as $i => $block) {
        if (is_array($block)) {
            if ('' === $block['blockName']) {
                continue;
            }
            if ('core/block' === $block['blockName']) {
                $id = (isset($block['attrs']['ref'])) ? $block['attrs']['ref'] : 0;

                if ($id) {
                    $content = get_post_field('post_content', $id);

                    $reusable_blocks = parse_blocks($content);
                    $js .= jma_ghb_get_block_js($reusable_blocks[0]);
                }
            } else {
                // Get js for the Block.
                $js .= jma_ghb_get_block_js($block);
            }
        }
    }

    return $js;
}
