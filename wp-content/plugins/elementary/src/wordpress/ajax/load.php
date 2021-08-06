<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_elementary_fetch_db_data', 'elementary_fetch_db_data' );

function elementary_fetch_db_data() {

    // if ( ( !wp_verify_nonce( $_POST['nonce'], "elementary_fetch_db_data") )
	// && ( !is_elementary_test_mode_enabled() ) ) {
	// 	exit();
	// }

    if ( current_user_can( 'manage_element' ) ) {

        $post_id = $content_list = $payload = $app_db_info = '';

        $field_value_array = $post_id_array = $atom_type_array = array();

        if ( array_key_exists( 'postID', $_POST ) ) {
    		$post_id = intval( $_POST['postID'] );
            // error_log('$post_id: ' . $post_id);
    	}

        if ( $post_id && is_numeric( $post_id ) ) {

            $shortcode_helper = new Elementary_Shortcode_Helper();
            global $wordpress_functions;

            $content_settings_store = $shortcode_helper->get_content_settings_store( $post_id );
            $content_settings = $shortcode_helper->get_content_settings( $content_settings_store );
            if ( $content_settings ) {
                $field_value_array = $content_settings['field_value'];
                $post_id_array = $content_settings['post_id_array'];
                $atom_type_array = $shortcode_helper->get_atom_type_array( $content_settings_store );
                $content_list = $wordpress_functions->get_data( $atom_type_array, $field_value_array, $post_id_array );
                // error_log(print_r($content_list, true));
            }

            $app_db_info = get_post_meta( $post_id, '_appDBInfo', true );

            $meta_keys = elementary_get_matching_keys( $post_id );


            foreach ( $meta_keys as $key ) {
                $key = sanitize_text_field($key);
                $payload['models'][$key] = get_post_meta( $post_id, '_' . $key, true );
            }

            $payload['models']['contentStore'] = '';
            $payload['models']['contentStore']['contentList'] = $content_list;
            $payload['models']['contentSettingsStore']['atomTypeArray'] = $atom_type_array;
            $payload['info']['appDBInfo'] = $app_db_info;

            print_r( json_encode( $payload, JSON_NUMERIC_CHECK ) );
        	wp_die();
        }
        else {
            $wordpress_functions->elementary_log( 'Invalid Post ID. Unable to load the element.' );
        }

    }
}
