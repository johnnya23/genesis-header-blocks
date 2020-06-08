<?php

function jma_ghb_get_block_css_js($block)
{
    $block = ( array ) $block;
    if (isset($block['blockName'])) {
        $name = $block['blockName'];
    }
    $css = $final_block  = array();
    $block_id = $js = '';

    if (! isset($name)) {
        return;
    }

    if (isset($block['attrs']) && is_array($block['attrs'])) {
        $blockattr = $block['attrs'];
        if (isset($blockattr['block_id'])) {
            $block_id = $blockattr['block_id'];
        }
    }

    /* this switch statement needs to mirror the one in
    ----- ultimate-addons-for-gutenberg/classes/class-uagb-helper  line 414
    */
    switch ($name) {
                case 'uagb/how-to':
                    $css += UAGB_Block_Helper::get_how_to_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_how_to_gfont($blockattr);
                    break;

                case 'uagb/section':
                    $css += UAGB_Block_Helper::get_section_css($blockattr, $block_id);
                    break;

                case 'uagb/advanced-heading':
                    $css += UAGB_Block_Helper::get_adv_heading_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_advanced_heading_gfont($blockattr);
                    break;

                case 'uagb/info-box':
                    $css += UAGB_Block_Helper::get_info_box_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_info_box_gfont($blockattr);
                    break;

                case 'uagb/buttons':
                    $css += UAGB_Block_Helper::get_buttons_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_buttons_gfont($blockattr);
                    break;

                case 'uagb/buttons-child':
                    $css += UAGB_Block_Helper::get_buttons_child_css($blockattr, $block_id);
                    break;

                case 'uagb/blockquote':
                    $css += UAGB_Block_Helper::get_blockquote_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_blockquote_gfont($blockattr);
                    $js .= UAGB_Block_JS::get_blockquote_js($blockattr, $block_id);
                    break;

                case 'uagb/testimonial':
                    $css += UAGB_Block_Helper::get_testimonial_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_testimonial_gfont($blockattr);
                    $js .= UAGB_Block_JS::get_testimonial_js($blockattr, $block_id);
                    break;

                case 'uagb/team':
                    $css += UAGB_Block_Helper::get_team_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_team_gfont($blockattr);
                    break;

                case 'uagb/social-share':
                    $css += UAGB_Block_Helper::get_social_share_css($blockattr, $block_id);
                    $js  .= UAGB_Block_JS::get_social_share_js($blockattr, $block_id);
                    break;

                case 'uagb/social-share-child':
                    $css += UAGB_Block_Helper::get_social_share_child_css($blockattr, $block_id);
                    break;

                case 'uagb/content-timeline':
                    $css += UAGB_Block_Helper::get_content_timeline_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_content_timeline_gfont($blockattr);
                    break;

                case 'uagb/restaurant-menu':
                    $css += UAGB_Block_Helper::get_restaurant_menu_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_restaurant_menu_gfont($blockattr);
                    break;

                case 'uagb/call-to-action':
                    $css += UAGB_Block_Helper::get_call_to_action_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_call_to_action_gfont($blockattr);
                    break;

                case 'uagb/post-timeline':
                    $css += UAGB_Block_Helper::get_post_timeline_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_post_timeline_gfont($blockattr);
                    break;

                case 'uagb/icon-list':
                    $css += UAGB_Block_Helper::get_icon_list_css($blockattr, $block_id);
                    // We have used the same buttons gfont function because the inputs to these functions are same.
                    // If need be please add a new function for Info Box and go ahead.
                    UAGB_Block_JS::blocks_buttons_gfont($blockattr);
                    break;

                case 'uagb/icon-list-child':
                    $css += UAGB_Block_Helper::get_icon_list_child_css($blockattr, $block_id);
                    break;

                case 'uagb/post-grid':
                    $css += UAGB_Block_Helper::get_post_grid_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_post_gfont($blockattr);
                    break;

                case 'uagb/post-carousel':
                    $css += UAGB_Block_Helper::get_post_carousel_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_post_gfont($blockattr);
                    break;

                case 'uagb/post-masonry':
                    $css += UAGB_Block_Helper::get_post_masonry_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_post_gfont($blockattr);
                    break;

                case 'uagb/columns':
                    $css += UAGB_Block_Helper::get_columns_css($blockattr, $block_id);
                    break;

                case 'uagb/column':
                    $css += UAGB_Block_Helper::get_column_css($blockattr, $block_id);
                    break;

                case 'uagb/cf7-styler':
                    $css += UAGB_Block_Helper::get_cf7_styler_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_cf7_styler_gfont($blockattr);
                    break;

                case 'uagb/marketing-button':
                    $css += UAGB_Block_Helper::get_marketing_btn_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_marketing_btn_gfont($blockattr);
                    break;

                case 'uagb/gf-styler':
                    $css += UAGB_Block_Helper::get_gf_styler_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_gf_styler_gfont($blockattr);
                    break;

                case 'uagb/table-of-contents':
                    $css += UAGB_Block_Helper::get_table_of_contents_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_table_of_contents_gfont($blockattr);
                    $js .= UAGB_Block_JS::get_table_of_contents_js($blockattr, $block_id);
                    break;

                case 'uagb/faq':
                    $css += UAGB_Block_Helper::get_faq_css($blockattr, $block_id);
                    UAGB_Block_JS::blocks_faq_gfont($blockattr);
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

            $inner_block_css_array = jma_ghb_get_block_css_js($final_block);
            $inner_block_css = $inner_block_css_array['css'];
            $js .= $inner_block_css_array['js'];

            $css_desktop = (isset($css['desktop']) ? $css['desktop'] : '');
            $css_tablet = (isset($css['tablet']) ? $css['tablet'] : '');
            $css_mobile = (isset($css['mobile']) ? $css['mobile'] : '');

            if (isset($inner_block_css['desktop'])) {
                $css['desktop'] = $css_desktop . $inner_block_css['desktop'];
                $css['tablet'] = $css_tablet . $inner_block_css['tablet'];
                $css['mobile'] = $css_mobile . $inner_block_css['mobile'];
            }
        }
    }

    return array('css' => $css, 'js' => $js);
}

function jma_ghb_get_stylesheet($blocks)
{
    $desktop = '';
    $tablet  = '';
    $mobile  = '';

    $tab_styling_css = '';
    $mob_styling_css = '';
    foreach ($blocks as $i => $block) {
        if (is_array($block)) {
            if ('' === $block['blockName']) {
                continue;
            }

            if ('core/block' === $block['blockName']) {
                $id = (isset($block['attrs']['ref'])) ? $block['attrs']['ref'] : 0;

                if ($id) {
                    $content = get_post_field('post_content', $id);

                    $outer_block = parse_blocks($content);
                    $block = $outer_block[0];
                }
            }

            $css_array = jma_ghb_get_block_css_js($block);
            $css = $css_array['css'];
            // Get CSS for the Block.


            if (isset($css['desktop'])) {
                $desktop .= $css['desktop'];
                $tablet  .= $css['tablet'];
                $mobile  .= $css['mobile'];
            }
        }
    }

    if (! empty($tablet)) {
        $tab_styling_css .= '@media only screen and (max-width: ' . UAGB_TABLET_BREAKPOINT . 'px) {';
        $tab_styling_css .= $tablet;
        $tab_styling_css .= '}';
    }

    if (! empty($mobile)) {
        $mob_styling_css .= '@media only screen and (max-width: ' . UAGB_MOBILE_BREAKPOINT . 'px) {';
        $mob_styling_css .= $mobile;
        $mob_styling_css .= '}';
    }
    $desktop .='p:empty {display:none}';

    return $desktop . $tab_styling_css . $mob_styling_css;
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
                    $js_array = jma_ghb_get_block_css_js($reusable_blocks[0]);
                    $js .= $js_array['js'];
                }
            } else {
                // Get js for the Block.
                $js_array = jma_ghb_get_block_css_js($block);
                $js .= $js_array['js'];
            }
        }
    }

    return $js;
}
