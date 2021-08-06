<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_elementary_create_new_field', 'elementary_create_new_field' );

function elementary_create_new_field() {
    $field_type = $field_key = '';

    $field_value_array = array();

    if ( array_key_exists( 'context', $_POST ) ) {
		$context = sanitize_text_field($_POST['context']);
	}

    if ( array_key_exists( 'contentSettingsStore', $_POST ) ) {
        $sanitize = new Elementary_Sanitize();
        $content_settings_store = $sanitize->sanitize_array($_POST['contentSettingsStore']);
	}


    $shortcode_helper = new Elementary_Shortcode_Helper();
    global $wordpress_functions;

    $content_settings = $shortcode_helper->get_content_settings( $content_settings_store );
    $atom_type_array = $shortcode_helper->get_atom_type_array( $content_settings_store );

    if ( array_key_exists( 'post_id_array', $content_settings ) ) {
		$post_id_array = $content_settings['post_id_array'];
	}

    $field_key = substr( $context, 0, 6 );
    if ( array_key_exists( $field_key, $atom_type_array ) ) {
		$field_type = $atom_type_array[$field_key];
	}
    else {
        $wordpress_functions->elementary_log('Invalid Field Key: ' . $field_key );
    }

    if ( $field_type ) {
        $field_value_array = $wordpress_functions->get_fields_list( $field_type, $post_id_array );
    }
    $field_value_array = array_values( $field_value_array );

    wp_send_json( $field_value_array );
}
