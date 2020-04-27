<?php


if (!function_exists('jma_ghb_add_header_input_box')) {
    function jma_ghb_add_header_input_box()
    {
        $screens = array('post', 'page');
        $screens = apply_filters('input_screens_filter', $screens);
        foreach ($screens as $screen) {
            add_meta_box(
                'jma_ghb_header_input_section',
                __('Current Page Header Content', 'jma_ghb_textdomain'),
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
        $screen_obj = get_current_screen();

        $default_featured = $screen_obj->post_type === 'page' ? 1 : 0;
        $page_values = array('change_header_default' => 0,
            'change_footer_default' => 0,
            'slider_id' => ''
        );


        $header_array = jma_gbh_get_header_footer('header', false);
        $footer_array = jma_gbh_get_header_footer('footer', false);

        if (get_post_meta($post->ID, '_jma_ghb_header_footer_key', true)) {
            $page_values = get_post_meta($post->ID, '_jma_ghb_header_footer_key', true);
        }
        //print_r($page_values);
        $slider_selections = array();
        $slider_selections = apply_filters('slider_array_filter', $slider_selections);

        ob_start();
        echo '<p></p>';
        echo '<label for="change_header_default">';
        _e('Change header display for this page', 'jma_ghb_textdomain');
        echo '</label><br/><br/> ';

        echo '<select name="header_id">';
        echo '<option value="0"'. selected($page_values['header_id'], '').'>Default</option>';
        foreach ($header_array as $i => $form_item) {
            echo '<option value="'.$i.'"'.selected($page_values['header_id'], $i).'>'.$form_item.'</option>';
        }
        echo '</select><br/><br/>';

        echo '<label for="slider_id">';
        _e('Image Choice -- if a "Featured Image" block was used in the header, you can change its display here', 'jma_ghb_textdomain');
        echo '</label><br/><br/> ';
        echo '<select name="slider_id">';
        echo '<option value="0"'.selected($page_values['slider_id'], '').'>Default</option>';
        echo '<option value="1"'.selected($page_values['slider_id'], '1').'>Featured Image</option>';
        if (count($slider_selections)) {
            foreach ($slider_selections as $i => $form_item) {
                echo '<option value="'.$i.'"'.selected($page_values['slider_id'], $i).'>'.$form_item.'</option>';
            }
        }
        echo '</select><br/><br/>';

        echo '<label for="change_footer_default">';
        _e('Change footer display for this page', 'jma_ghb_textdomain');
        echo '</label><br/><br/> ';

        echo '<select name="footer_id">';
        echo '<option value="0"'. selected($page_values['footer_id'], '').'>Default</option>';
        foreach ($footer_array as $i => $form_item) {
            echo '<option value="'.$i.'"'.selected($page_values['footer_id'], $i).'>'.$form_item.'</option>';
        }
        echo '</select><br/><br/>';

        /*
                echo '<label for="widget_area">';
                _e('Add page by page content to display over the featured image (or slider)', 'jma_ghb_textdomain');
                echo '</label><br/><br/> ';

                $content = !isset($page_values['widget_area'])? '': $page_values['widget_area'];
                wp_editor(htmlspecialchars_decode($content), '_jma_ghb_widget_area', array(
                    "media_buttons" => true
            ));*/

        $x = ob_get_contents();
        $x = apply_filters('jma_ghb_current_page_options', $x, $page_values);
        ob_end_clean();
        echo str_replace("\r\n", '', $x);
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
        foreach ($values as $i => $value) {
            $clean_data[$i] = wp_kses_post($value);
        }

        // Update the meta field in the database.
        update_post_meta($post_id, '_jma_ghb_header_footer_key', $clean_data);
    }
}
add_action('save_post', 'jma_ghb_save_header_postdata');
