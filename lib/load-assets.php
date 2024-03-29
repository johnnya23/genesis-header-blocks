<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function jma_ghb_enqueue_scripts()
{
    $min = WP_DEBUG? '': '.min';
    wp_register_style('jma-ghb-style', JMA_GHB_BASE_URI .'style' . $min .'.css');
    wp_enqueue_style('jma-ghb-style');
    if (function_exists('jma_gbs_detect_block')) {
        $header_post_id_array = jma_ghb_location_switching('header');
        $header_post_id = $header_post_id_array['target'];

        $footer_post_id_array = jma_ghb_location_switching('footer');
        $footer_post_id = $footer_post_id_array['target'];
        if ((
            jma_gbs_detect_block(
                array('name' =>  'kadence/rowlayout', 'post' =>  $header_post_id)
            ) || jma_gbs_detect_block(array('name' =>  'kadence/rowlayout', 'post' =>  $footer_post_id))
        ) && ! wp_style_is('kadence-blocks-rowlayout', 'enqueued')
    ) {
            wp_enqueue_style('kadence-blocks-rowlayout');
        }
    }
}
add_action('admin_enqueue_scripts', 'jma_ghb_enqueue_scripts', 999);


function jma_ghb_template_redirect()
{
    add_action('wp_enqueue_scripts', 'jma_ghb_enqueue_scripts', 999);
}
add_action('template_redirect', 'jma_ghb_template_redirect', 15);

/*
 * Add script
 */
 function jma_ghb_add_script()
 {
     ?>
   <script>
     jQuery(document).ready( function($) {
       function ct_media_upload(button_class) {
         var _custom_media = true,
         _orig_send_attachment = wp.media.editor.send.attachment;
         $('body').on('click', button_class, function(e) {
           var button_id = '#'+$(this).attr('id');
           var send_attachment_bkp = wp.media.editor.send.attachment;
           var button = $(button_id);
           _custom_media = true;
           wp.media.editor.send.attachment = function(props, attachment){
             if ( _custom_media ) {
               $('#category-image-id').val(attachment.id);
               $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
               $('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
             } else {
               return _orig_send_attachment.apply( button_id, [props, attachment] );
             }
            }
         wp.media.editor.open(button);
         return false;
       });
     }
     ct_media_upload('.ct_tax_media_button.button');
     $('body').on('click','.ct_tax_media_remove',function(){
       $('#category-image-id').val('');
       $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
     });
     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
     $(document).ajaxComplete(function(event, xhr, settings) {
       var queryStringArr = settings.data.split('&');
       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
         var xml = xhr.responseXML;
         $response = $(xml).find('term_id').text();
         if($response!=""){
           // Clear the thumb image
           $('#category-image-wrapper').html('');
         }
       }
     });
   });
 </script>
 <?php
 }

add_action('admin_footer', 'jma_ghb_add_script');
