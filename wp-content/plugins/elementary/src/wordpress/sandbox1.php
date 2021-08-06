<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pauple_elementary_sandbox1() {
    echo '<div class="wrap"><h1>Sandbox 1</h1></div>';
    echo '<style>body{font-size:16px}</style>';


    global $wordpress_functions;
    $shortcode_helper = new Elementary_Shortcode_Helper();

    $post_id_array = array('1', '175');
    $atom_type_array = array(
        'field1'	=> 'text',
        'field2'	=> 'image',
        'field3'	=> 'text',
        'field4'	=> 'link',
    );

    $fields_list = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
    $wordpress_functions->print_array(array_keys($fields_list));
    $wordpress_functions->print_array($fields_list);
    echo get_woocommerce_currency_symbol();

    $fields_list1 = array(
        'field1'	=> 'Title',
        'field2'	=> 'Product Category Image',
        'field3'	=> 'Title',
        'field4'	=> 'Post URL',
    );

    $post_id_array1 = array('47', '31');

    $term_image = $wordpress_functions->get_data($atom_type_array, $fields_list1, $post_id_array1 );
    echo "term-image: ";
    echo get_the_terms('47');
    echo "term-image2: ";
    $wordpress_functions->print_array($term_image);
}
