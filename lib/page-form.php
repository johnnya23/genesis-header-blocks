<?php


if (!function_exists('jma_ghb_add_header_input_box')) {
    function jma_ghb_add_header_input_box()
    {
        $screens = array();
        $cpts = jma_ghb_get_cpt();
        foreach ($cpts as $slug => $obj) {
            $screens[] = $slug;
        }
        $screens = apply_filters('jma_ghb_input_screens_filter', $screens);
        foreach ($screens as $screen) {
            add_meta_box(
                'jma_ghb_header_input_section',
                'Current Page Configuration',
                'jma_ghb_header_input_box',
                $screen,
                'side',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'jma_ghb_add_header_input_box');

/*
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
if (!function_exists('jma_ghb_header_input_box')) {
    function jma_ghb_header_input_box($post)
    {

        // Add an nonce field so we can check for it later.
        wp_nonce_field('jma_ghb_header_input_box', 'jma_ghb_header_input_box_nonce');

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */



        if (get_post_meta($post->ID, '_jma_ghb_header_footer_key', true)) {
            $page_options = get_post_meta($post->ID, '_jma_ghb_header_footer_key', true);
        }


        $header_array = $footer_array = array();

        echo '<p></p>';

        $header_array = jma_ghb_header_footer_list('header', false);
        echo '<label for="header_id">';
        echo 'Change which header is displayed on this page';
        echo '</label><br/><br/> ';
        echo '<select name="header_id">';
        echo '<option value="0"'. selected($page_options['header_id'], '').'>Default</option>';
        foreach ($header_array as $i => $form_item) {
            echo '<option value="'.$i.'"'.selected($page_options['header_id'], $i).'>'.$form_item.'</option>';
        }
        echo '</select><br/><br/>';


        $slider_selections = array();
        $slider_selections = apply_filters('jma_ghb_slider_array_filter', $slider_selections);
        echo '<label for="slider_id">';
        echo 'Image Choice -- if a "Featured Image" block was used in the header, you can change its display here';
        echo '</label><br/><br/> ';
        echo '<select name="slider_id">';
        echo '<option value="0"'.selected($page_options['slider_id'], '').'>Default</option>';
        echo '<option value="jma_featured"'.selected($page_options['slider_id'], 'jma_featured').'>Featured Image</option>';
        if (count($slider_selections)) {
            foreach ($slider_selections as $i => $form_item) {
                echo '<option value="'.$i.'"'.selected($page_options['slider_id'], $i).'>'.$form_item.'</option>';
            }
        }
        echo '</select><br/><br/>';

        echo '<label for="sticky-header">';
        echo 'Sticky header stays in same place as main content rolls over it';
        echo '</label><br/><br/> ';
        echo '<select name="sticky-header">';
        echo '<option value="0"'.selected($page_options['sticky-header'], 0).'>normal</option>';
        echo '<option value="1"'.selected($page_options['sticky-header'], 1).'>sticky</option>';
        echo '</select><br/><br/>';

        $footer_array = jma_ghb_header_footer_list('footer', false);
        echo '<label for="footer_id">';
        echo 'Change which footer is displayed on this page';
        echo '</label><br/><br/> ';
        echo '<select name="footer_id">';
        echo '<option value="0"'. selected($page_options['footer_id'], '').'>Default</option>';
        foreach ($footer_array as $i => $form_item) {
            echo '<option value="'.$i.'"'.selected($page_options['footer_id'], $i).'>'.$form_item.'</option>';
        }
        echo '</select><br/><br/>';


        echo '<label for="widget_area">';
        echo 'Add page by page content to display over the featured image (or slider)';
        echo '</label><br/><br/> ';

        $content = !isset($page_options['widget_area'])? '': $page_options['widget_area'];
        wp_editor(htmlspecialchars_decode($content), 'widget_area', array(
                    "media_buttons" => true
            ));
    }
}
/*
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
if (!function_exists('jma_ghb_save_header_postdata')) {
    function jma_ghb_save_header_postdata($post_id)
    {
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if (!isset($_POST['jma_ghb_header_input_box_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['jma_ghb_header_input_box_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'jma_ghb_header_input_box')) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        if ('page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /* OK, its safe for us to save the data now. */

        // Sanitize user input.  htmlspecialchars($_POST['_right_sb_wysiwyg']);
        $values = $_POST;
        //$values['widget_area'] = $_POST[ '_jma_ghb_widget_area'];
        $clean_data = array();
        $fields = array('sticky-header', 'header_id', 'slider_id', 'footer_id');
        foreach ($fields as $field) {
            if (is_string($values[$field])) {
                $clean_data[$field] = wp_kses_post($values[$field]);
            }
        }

        // Update the meta field in the database.
        update_post_meta($post_id, '_jma_ghb_header_footer_key', $clean_data);
    }
}
add_action('save_post', 'jma_ghb_save_header_postdata');
