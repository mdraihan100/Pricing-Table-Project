<?php
/*
Plugin Name: Pricing Table
Description: A plugin to create and manage Pricing Table  two.
Version: 1.0
Author:raihan
*/

// Register Custom Post Type
function _pricing_table_two_post_type() {
    $labels = array(
        'name'               => 'Pricing Table',
        'singular_name'      => 'Pricing Table',
        'menu_name'          => 'Pricing Table',
        'name_admin_bar'     => 'Pricing Table',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Item',
        'new_item'           => 'New Pricing Table',
        'edit_item'          => 'Edit Pricing Table',
        'view_item'          => 'View Pricing Table',
        'all_items'          => 'All Pricing Table',
        'search_items'       => 'Search Pricing Table',
        'parent_item_colon'  => 'Parent Pricing Table:',
        'not_found'          => 'No Pricing Table found.',
        'not_found_in_trash' => 'No Pricing Table found in Trash.'

    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'pricing-table'),        
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title','editor')
    );

    register_post_type('pricing_table', $args);
}
add_action('init', '_pricing_table_two_post_type');



function pricing_table_meta_box() {
    add_meta_box(
        'pricing_table_details',
        'pricing_table details',
        'pricing_table_details_callback',
        'pricing_table',
 
    );
}
add_action('add_meta_boxes', 'pricing_table_meta_box');

function pricing_table_details_callback($post) {
    // Add nonce for security and authentication.
    wp_nonce_field('pricing_table_nonce_action', 'pricing_table_nonce'); // this line//

    // Retrieve existing values from the database.
    $subtitle = get_post_meta($post->ID, 'pricing_table_title', true);
    $short_description = get_post_meta($post->ID, 'pricing_table_description', true);
    $price = get_post_meta($post->ID, 'pricing_table_price', true);
    $frequency = get_post_meta($post->ID, 'pricing_table_frequency_item', true);
    $button_text = get_post_meta($post->ID, 'pricing_table_button', true);
    $button_url = get_post_meta($post->ID, 'pricing_button_url', true);
    $css_classes = get_post_meta($post->ID, 'pricing_button_classes', true);
    $upload_icon = get_post_meta($post->ID, 'pricing_button_icon', true);
    
    // Display fields
    ?>
    <table>
        <tr>
            <td>Add Title:</td>
            <td><input type="text" name="pricing_table_title" value="<?php echo esc_attr($subtitle); ?>"></td>
        </tr>
        <tr>
            <td> Description:</td>
            <td><input type="text" name="pricing_table_description" value="<?php echo esc_attr($short_description); ?>"></td>
        </tr>
        <tr>
            <td>Price:</td>
            <td><input type="number" name="pricing_table_price" value="<?php echo esc_attr($price); ?>"></td>
        </tr>
        <tr>
            <td>Pricing Frequency Item:</td>
            <td><input type="text" name="pricing_table_frequency_item" value="<?php echo esc_attr($frequency); ?>"></td>
        </tr>
     
        <tr>
            <td>Button Text:</td>
            <td><input type="text" name="pricing_table_button" value="<?php echo esc_attr($button_text); ?>"></td>
        </tr>
        <tr>
            <td>Button URL:</td>
            <td><input type="text" name="pricing_button_url" value="<?php echo esc_attr($button_url); ?>"></td>
        </tr>
   
        <tr>
            <td>CSS Classes:</td>
            <td><input type="text" name="pricing_button_classes" value="<?php echo esc_attr($css_classes); ?>"></td>
        </tr>
        <h1><?php echo "Hello Pricing Table New" ?></h1>
    </table>
    <?php
}

add_action( 'save_post_pricing_table', 'bpt_save_pricing_table_meta' );
function bpt_save_pricing_table_meta($post_id) {
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

    // Sanitize user input.
    $subtitle = sanitize_text_field($_POST['pricing_table_title']);
    $short_description = sanitize_text_field($_POST['pricing_table_description']);
    $price = sanitize_text_field($_POST['pricing_table_price']);
    $frequency = sanitize_text_field($_POST['pricing_table_frequency_item']);
    $button_text = sanitize_text_field($_POST['pricing_table_button']);
    $button_url = sanitize_text_field($_POST['pricing_button_url']);
    $css_classes = sanitize_text_field($_POST['pricing_button_classes']);

    // Update the meta fields.
    update_post_meta($post_id, 'pricing_table_title', $subtitle);
    update_post_meta($post_id, 'pricing_table_description', $short_description);
    update_post_meta($post_id, 'pricing_table_price', $price);
    update_post_meta($post_id, 'pricing_table_frequency_item', $frequency);
    update_post_meta($post_id, 'pricing_table_button', $button_text);
    update_post_meta($post_id, 'pricing_button_url', $button_url);
    update_post_meta($post_id, 'pricing_button_classes', $css_classes);
}

?>
