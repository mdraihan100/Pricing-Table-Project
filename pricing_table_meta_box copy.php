<?php

function pricing_table_meta_boxes_old()
{
    // Array of meta boxes
    $meta_boxes = array(
        array('id' => 1, 'key' => 'pricing_table_card_1', 'title' => 'Pricing Table Card 1'),
        array('id' => 2, 'key' => 'pricing_table_card_2', 'title' => 'Pricing Table Card 2'),
        array('id' => 3, 'key' => 'pricing_table_card_3', 'title' => 'Pricing Table Card 3'),
        array('id' => 4, 'key' => 'pricing_table_card_4', 'title' => 'Pricing Table Card 4')
    );

    foreach ($meta_boxes as $meta_box) {
        add_meta_box(
            $meta_box['key'],
            $meta_box['title'],
            'pricing_table_details_callback',
            'pricing_table',
            'advanced',
            'default',
            ["id" => $meta_box['id']]
        );
    }
}
function pricing_table_meta_boxes(){
    
}
add_action('add_meta_boxes', 'pricing_table_meta_boxes');

function pricing_table_details_callback($post, $args)
{
    $pricing_card_id = $args['args']['id'];

    // Add nonce for security and authentication.
    wp_nonce_field('pricing_table_nonce_action', 'pricing_table_nonce');

    // Retrieve existing values from the database.
    $subtitle = get_post_meta($post->ID, "pricing_table_title_$pricing_card_id", true);
    $short_description = get_post_meta($post->ID, "pricing_table_description_$pricing_card_id", true);
    $price = get_post_meta($post->ID, "pricing_table_price_$pricing_card_id", true);
    $frequency = get_post_meta($post->ID, "pricing_table_frequency_item_$pricing_card_id", true);
    $button_text = get_post_meta($post->ID, "pricing_table_button_$pricing_card_id", true);
    $button_url = get_post_meta($post->ID, "pricing_button_url_$pricing_card_id", true);
    $css_classes = get_post_meta($post->ID, "pricing_button_classes_$pricing_card_id", true);

    ?>
    <table>
        <tr>
            <td>Add Title:</td>
            <td><input type="text" name="pricing_table_title_<?php echo $pricing_card_id; ?>"
                    value="<?php echo esc_attr($subtitle); ?>"></td>
        </tr>
        <tr>
            <td> Add Price:</td>
            <td><input type="number" name="pricing_table_price_<?php echo $pricing_card_id; ?>"
                    value="<?php echo esc_attr($price); ?>"></td>
        </tr>
        <tr>
            <td> Description:</td>
            <td><input type="text" name="pricing_table_description_<?php echo $pricing_card_id; ?>"
                    value="<?php echo esc_attr($short_description); ?>">
            </td>
        </tr>
        <tr>
            <td>Pricing Frequency Item:</td>
            <td><input type="text" name="pricing_table_frequency_item_<?php echo $pricing_card_id; ?>"
                    value="<?php echo esc_attr($frequency); ?>"></td>
        </tr>
        <tr>
            <td>Button Text:</td>
            <td><input type="text" name="pricing_table_button_<?php echo $pricing_card_id; ?>"
                    value="<?php echo esc_attr($button_text); ?>"></td>
        </tr>
        <tr>
            <td>Button URL:</td>
            <td><input type="text" name="pricing_button_url_<?php echo $pricing_card_id; ?>"
                    value="<?php echo esc_attr($button_url); ?>"></td>
        </tr>
        <tr>
            <td>CSS Classes:</td>
            <td><input type="text" name="pricing_button_classes_<?php echo $pricing_card_id; ?>"
                    value="<?php echo esc_attr($css_classes); ?>"></td>
        </tr>
    </table>
    <?php
}



// saved pricing table data //

function pricing_table_meta_data_save($post_id)
{
    // Check if nonce is set.
    if (!isset($_POST['pricing_table_nonce'])) {
        return $post_id;
    }
    $nonce = $_POST['pricing_table_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'pricing_table_nonce_action')) {
        return $post_id;
    }

    // Check the user's permissions.
    if ('pricing_table' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    // Array of meta box IDs
    $meta_boxes = array(1, 2, 3, 4);

    // Loop through each meta box and save the data
    foreach ($meta_boxes as $meta_box_id) {
        $fields = array(
            "pricing_table_title_$meta_box_id",
            "pricing_table_description_$meta_box_id",
            "pricing_table_price_$meta_box_id",
            "pricing_table_frequency_item_$meta_box_id",
            "pricing_table_button_$meta_box_id",
            "pricing_button_url_$meta_box_id",
            "pricing_button_classes_$meta_box_id"
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $sanitized_value = sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $field, $sanitized_value);
            }
        }
    }
}

add_action('save_post_pricing_table', 'pricing_table_meta_data_save');


