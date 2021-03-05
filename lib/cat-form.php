<?php

function jma_ghb_category_fields($term)
{
    // we check the name of the action because we need to have different output
    // if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
    $header_val = get_term_meta($term->term_id, 'header_val', true);
    $footer_val = get_term_meta($term->term_id, 'footer_val', true);
    $image_val = get_term_meta($term->term_id, 'category-image-id', true);
    $sticky = get_term_meta($term->term_id, 'sticky-header', true);

    $header_array = jma_ghb_header_footer_list('header', false);
    $footer_array = jma_ghb_header_footer_list('footer', false);
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

    echo '<th valign="top" scope="row"><label for="term_fields">' . __('Sticky Header') . '</label></th>';
    echo '<td>';
    echo '<label for="sticky-header">';
    echo 'Sticky header stays in same place as main content rolls over it';
    echo '</label><br/><br/> ';
    echo '<select name="term_fields[sticky-header]">';
    echo '<option value="0"'.selected($sticky, 0).'>normal</option>';
    echo '<option value="1"'.selected($sticky, 1).'>sticky</option>';
        echo '</select><br/><br/>';
    echo '</td>';
    echo '</tr>'; ?>
  <tr class="form-field term-group-wrap">
    <th scope="row">
      <label for="category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
    </th>
    <td>
        <p>this image will replace the feature image in the header if the block allows it.</p>
      <input type="hidden" id="category-image-id" name="term_fields[category-image-id]" value="<?php echo $image_val; ?>">
      <div id="category-image-wrapper">
        <?php if ($image_val) {
        ?>
          <?php echo wp_get_attachment_image($image_val, 'thumbnail'); ?>
        <?php
    } ?>
      </div>
      <p>
        <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e('Add Image', 'hero-theme'); ?>" />
        <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e('Remove Image', 'hero-theme'); ?>" />
      </p>
    </td>
  </tr>
<?php
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
