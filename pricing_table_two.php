<?php
/*
Plugin Name: Pricing Table
Description: A plugin to create and manage Pricing Table  two.
Version: 1.0.0
PHP: 8.0
Author:raihan
*/

// Register Custom Post Type
function _pricing_table_two_post_type()
{
    $labels = array(
        'name' => 'Pricing Table',
        'singular_name' => 'Pricing Table',
        'menu_name' => 'Pricing Table',
        'name_admin_bar' => 'Pricing Table',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Item',
        'new_item' => 'New Pricing Table',
        'edit_item' => 'Edit Pricing Table',
        'view_item' => 'View Pricing Table',
        'all_items' => 'All Pricing Table',
        'search_items' => 'Search Pricing Table',
        'parent_item_colon' => 'Parent Pricing Table:',
        'not_found' => 'No Pricing Table found.',
        'not_found_in_trash' => 'No Pricing Table found in Trash.'

    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'pricing-table'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor')
    );

    register_post_type('pricing_table', $args);
}
add_action('init', '_pricing_table_two_post_type');

// Add Short code in pricing table plugin // 

add_shortcode('pricing_table', 'shortcode_function');
function shortcode_function($atts)
{
    $a = shortcode_atts(
        array(
            'post_id' => null,  //default value
            'template' => 'template_one' //default value
        ), $atts);

    $template_name = $atts['template'];

    ob_start();

    // var_dump($template_name);
    // var_dump(get_post_meta($atts["post_id"], "pricing_table_title_1",  true));
    include ("templates/$template_name.php");


    return ob_get_clean();
}

// include meta box file //

include ("pricing_table_meta_box.php");


// Enque style.cssfile // 
/**
 * Proper way to enqueue scripts and styles
 */
function wpdocs_theme_name_scripts() {
	wp_enqueue_style( 'style-name', get_stylesheet_uri(). '/css/style.css', array(), 'true', );
	// wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );


// Enque js file 
function adding_scripts() {
    wp_enqueue_script( 'script-id', get_template_directory_uri() . 'js/script.js', array(), '1.0.0', true );
    wp_enqueue_script('my_amazing_script');
} 

add_action( 'wp_enqueue_scripts', 'adding_scripts', 999 ); 