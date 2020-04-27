<?php

function jma_ghb_category_fields($term)
{
    // we check the name of the action because we need to have different output
    // if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
    $header_val = get_term_meta($term->term_id, 'header_val', true);
    $footer_val = get_term_meta($term->term_id, 'footer_val', true);
    $header_array = jma_gbh_get_header_footer('header', false);
    $footer_array = jma_gbh_get_header_footer('footer', false);
    echo '<tr class="form-field">';
    echo '<th valign="top" scope="row"><label for="term_fields">' . __('Header Display') . '</label></th>';
    echo '<td>';
    echo '<label for="term_fields[header_val]">';
    echo _e('You can change the header for this display here');
    echo '</label><br/><br/> ';
    echo '<select name="term_fields[header_val]">';
    echo '<option value="0"'. selected($header_val, '').'>Default</option>';
    foreach ($header_array as $i => $form_item) {
        echo '<option value="'.$i.'"'.selected($header_val, $i).'>'.$form_item.'</option>';
    }
    echo '</select><br/><br/>';
    echo '</td>';
    echo '</tr>';
    echo '<tr class="form-field">';
    echo '<th valign="top" scope="row"><label for="term_fields">' . __('Footer Display') . '</label></th>';
    echo '<td>';
    echo '<label for="term_fields[footer_val]">';
    echo _e('You can change the footer for this display here');
    echo '</label><br/><br/> ';
    echo '<select name="term_fields[footer_val]">';
    echo '<option value="0"'. selected($footer_val, '').'>Default</option>';
    foreach ($footer_array as $i => $form_item) {
        echo '<option value="'.$i.'"'.selected($footer_val, $i).'>'.$form_item.'</option>';
    }
    echo '</select><br/><br/>';
    echo '</td>';
    echo '</tr>';
}

// Add the fields, using our callback function
// if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
//add_action('category_add_form_fields', 'jma_ghb_category_fields', 10, 2);
add_action('category_edit_form_fields', 'jma_ghb_category_fields', 10, 2);

function jma_ghb_save_category_fields($term_id)
{
    if (!isset($_POST['term_fields'])) {
        return;
    }

    foreach ($_POST['term_fields'] as $key => $value) {
        update_term_meta($term_id, $key, sanitize_text_field($value));
    }
}

// Save the fields values, using our callback function
// if you have other taxonomy name, replace category with the name of your taxonomy. ex: edited_book, create_book
add_action('edited_category', 'jma_ghb_save_category_fields', 10, 2);
//add_action('create_category', 'jma_ghb_save_category_fields', 10, 2);

// $term_id = 4, $key = 'header_val'
//echo get_term_meta($term_id, 'header_val', true);

// $term_id = 4, $key = 'color_code'
//echo get_term_meta(4, 'color_code', true);
