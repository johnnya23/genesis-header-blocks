<?php

function jma_ghb_get_block_css($block)
{

            // @codingStandardsIgnoreStart

    $block = ( array ) $block;

    $name = $block['blockName'];
    $css  = array();
    $block_id = '';

    if (! isset($name)) {
        return;
    }

    if (isset($block['attrs']) && is_array($block['attrs'])) {
        $blockattr = $block['attrs'];
        if (isset($blockattr['block_id'])) {
            $block_id = $blockattr['block_id'];
        }
    }

    UAGB_Helper::$current_block_list[] = $name;

    if (strpos($name, 'uagb/') !== false) {
        UAGB_Helper::$uag_flag = true;
    }

    switch ($name) {
                case 'uagb/section':
                    $css += UAGB_Block_Helper::get_section_css($blockattr, $block_id);
                    break;

                case 'uagb/advanced-heading':
                    $css += UAGB_Block_Helper::get_adv_heading_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_advanced_heading_gfont($blockattr);
                    break;

                case 'uagb/info-box':
                    $css += UAGB_Block_Helper::get_info_box_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_info_box_gfont($blockattr);
                    break;

                case 'uagb/buttons':
                    $css += UAGB_Block_Helper::get_buttons_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_buttons_gfont($blockattr);
                    break;

                case 'uagb/blockquote':
                    $css += UAGB_Block_Helper::get_blockquote_css($blockattr, $block_id);
                     UAGB_Block_Helper::blocks_blockquote_gfont($blockattr);
                    break;

                case 'uagb/testimonial':
                    $css += UAGB_Block_Helper::get_testimonial_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_testimonial_gfont($blockattr);
                    break;

                case 'uagb/team':
                    $css += UAGB_Block_Helper::get_team_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_team_gfont($blockattr);
                    break;

                case 'uagb/social-share':
                    $css += UAGB_Block_Helper::get_social_share_css($blockattr, $block_id);
                    break;

                case 'uagb/content-timeline':
                    $css += UAGB_Block_Helper::get_content_timeline_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_content_timeline_gfont($blockattr);
                    break;

                case 'uagb/restaurant-menu':
                    $css += UAGB_Block_Helper::get_restaurant_menu_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_restaurant_menu_gfont($blockattr);
                    break;

                case 'uagb/call-to-action':
                    $css += UAGB_Block_Helper::get_call_to_action_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_call_to_action_gfont($blockattr);
                    break;

                case 'uagb/post-timeline':
                    $css += UAGB_Block_Helper::get_post_timeline_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_post_timeline_gfont($blockattr);
                    break;

                case 'uagb/icon-list':
                    $css += UAGB_Block_Helper::get_icon_list_css($blockattr, $block_id);
                     UAGB_Block_Helper::blocks_icon_list_gfont($blockattr);
                    break;

                case 'uagb/post-grid':
                    $css += UAGB_Block_Helper::get_post_grid_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_post_gfont($blockattr);
                    break;

                case 'uagb/post-carousel':
                    $css += UAGB_Block_Helper::get_post_carousel_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_post_gfont($blockattr);
                    break;

                case 'uagb/post-masonry':
                    $css += UAGB_Block_Helper::get_post_masonry_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_post_gfont($blockattr);
                    break;

                case 'uagb/columns':
                    $css += UAGB_Block_Helper::get_columns_css($blockattr, $block_id);
                    break;

                case 'uagb/column':
                    $css += UAGB_Block_Helper::get_column_css($blockattr, $block_id);
                    break;

                case 'uagb/cf7-styler':
                    $css += UAGB_Block_Helper::get_cf7_styler_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_cf7_styler_gfont($blockattr);
                    break;

                case 'uagb/marketing-button':
                    $css += UAGB_Block_Helper::get_marketing_btn_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_marketing_btn_gfont($blockattr);
                    break;

                case 'uagb/gf-styler':
                    $css += UAGB_Block_Helper::get_gf_styler_css($blockattr, $block_id);
                     UAGB_Block_Helper::blocks_gf_styler_gfont($blockattr);
                    break;

                case 'uagb/table-of-contents':
                    $css += UAGB_Block_Helper::get_table_of_contents_css($blockattr, $block_id);
                    UAGB_Block_Helper::blocks_table_of_contents_gfont($blockattr);
                    break;

                default:
                    // Nothing to do here.
                    break;
            }

    if (isset($block['innerBlocks'])) {
        foreach ($block['innerBlocks'] as $j => $inner_block) {
            if ('core/block' == $inner_block['blockName']) {
                $id = (isset($inner_block['attrs']['ref'])) ? $inner_block['attrs']['ref'] : 0;

                if ($id) {
                    $content = get_post_field('post_content', $id);

                    $reusable_blocks = UAGB_Helper::parse($content);

                    UAGB_Helper::$stylesheet .= $UAGB_Helper::get_stylesheet($reusable_blocks);
                }
            } else {
                // Get CSS for the Block.
                $inner_block_css = jma_ghb_get_block_css($inner_block);

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
    }

    return $css;

    // @codingStandardsIgnoreEnd
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

                    $reusable_blocks = UAGB_Helper::parse($content);
                }
            } else {
                // Get CSS for the Block.
                $css = jma_ghb_get_block_css($block);

                if (isset($css['desktop'])) {
                    $desktop .= $css['desktop'];
                    $tablet  .= $css['tablet'];
                    $mobile  .= $css['mobile'];
                }
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











function jma_ghb_get_block_js($block)
{

            // @codingStandardsIgnoreStart

    $block = ( array ) $block;

    $name = $block['blockName'];
    $js  = '';

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
            // Get JS for the Block.
            $js .= jma_ghb_get_block_js($inner_block);
        }
    }

    return $js;

    // @codingStandardsIgnoreEnd
}


function jma_ghb_get_scripts($blocks)
{
    foreach ($blocks as $i => $block) {
        if (is_array($block)) {

                        // Get JS for the Block.
            $script .= jma_ghb_get_block_js($block);
        }
    }

    if (! empty($script)) {
        $script = '( function( $ ) { ' . $script . '})(jQuery);';
    }
}