<?php

if (! defined('ABSPATH')) {
   exit;
} // Exit if accessed directly

function jma_ghb_category_fields($term)
{
    // we check the name of the action because we need to have different output
    // if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
    $meta = get_term_meta($term->term_id);
    $header_val = $footer_val = $image_val = '';

    if (count($meta)) {
        $header_val = $meta['header_val'][0];
        $footer_val = $meta['footer_val'][0];
        $image_val = $meta['category-image-id'][0];
    }
    $header_array = jma_ghb_header_footer_list('header', false);
    $footer_array = jma_ghb_header_footer_list('footer', false);

    echo '<tr class="form-field">';
    echo '<th valign="top" scope="row"><label for="term_fields">Header Display</label></th>';
    echo '<td>';
    echo '<label for="term_fields[header_val]">';
    echo 'You can change the header for this display here';
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
    echo '<th valign="top" scope="row"><label for="term_fields">Footer Display</label></th>';
    echo '<td>';
    echo '<label for="term_fields[footer_val]">';
    echo 'You can change the footer for this display here';
    echo '</label><br/><br/> ';
    echo '<select name="term_fields[footer_val]">';
    echo '<option value="0"'. selected($footer_val, '').'>Default</option>';
    foreach ($footer_array as $i => $form_item) {
        echo '<option value="'.$i.'"'.selected($footer_val, $i).'>'.$form_item.'</option>';
    }
    echo '</select><br/><br/>';
    echo '</td>';
    echo '</tr>';

    echo '<tr class="form-field term-group-wrap">';
    echo '<th scope="row">';
    echo '<label for="category-image-id">Image</label>';
    echo '</th>';
    echo '<td>';
    echo '<p>this image will replace the feature image in the header if the block allows it.</p>';
    echo '<input type="hidden" id="category-image-id" name="term_fields[category-image-id]" value="' . $image_val . '">';
    echo '<div id="category-image-wrapper">';
    if ($image_val) {
        echo wp_get_attachment_image($image_val, 'thumbnail');
    }
    echo '</div>';
    echo '<p>';
    echo '<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="Add Image" />';
    echo '<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="Remove Image" />';
    echo '</p>';
    echo '</td>';
    echo '</tr>';
}

// Add the fields, using our callback function
// if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
//add_action('category_add_form_fields', 'jma_ghb_category_fields', 10, 2);
//
$taxonomies = get_taxonomies();
foreach ($taxonomies as $taxonomy) {
    add_action($taxonomy . '_edit_form_fields', 'jma_ghb_category_fields', 10, 2);
}
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
foreach ($taxonomies as $taxonomy) {
    add_action('edited_'.$taxonomy, 'jma_ghb_save_category_fields', 10, 2);
}

//add_action('create_category', 'jma_ghb_save_category_fields', 10, 2);

// $term_id = 4, $key = 'header_val'
//echo get_term_meta($term_id, 'header_val', true);

// $term_id = 4, $key = 'color_code'
//echo get_term_meta(4, 'color_code', true);
