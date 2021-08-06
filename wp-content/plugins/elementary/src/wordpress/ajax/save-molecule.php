<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Process the AJAX while saving the molecule */

add_action( 'wp_ajax_elementary_save_to_database', 'elementary_save_to_database' );

function elementary_save_to_database() {
    global $wordpress_functions;
    $validate = new Elementary_Validate();

	if ( ( !wp_verify_nonce( $_POST['nonce'], "elementary_save_to_database") )
	&& ( !is_elementary_test_mode_enabled() ) ) {
		exit();
	}

    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {

        $app_db_info = array();

        if ( array_key_exists( 'postID', $_POST ) ) {
    		$post_id = intval( $_POST['postID'] );
    	}
    	else {
    		$wordpress_functions->elementary_log('Post ID not available during save.');
    	}

    	if ( array_key_exists( 'title', $_POST ) ) {
    		$post_title = sanitize_title( $_POST['title'] );
    	}
    	else {
    		$wordpress_functions->elementary_log('Post Title not available during save.');
    	}

    	if ( array_key_exists( 'appDBInfo', $_POST ) ) {
    		$app_db_info['version'] = sanitize_text_field($_POST['appDBInfo']['version']);
            // $wordpress_functions->elementary_log('$app_db_info: '.$app_db_info['version']);
    	}
    	else {
    		$wordpress_functions->elementary_log('appDBInfo not available during save.');
    	}

    	if ( array_key_exists( 'payload', $_POST ) ) {
    		$sanitize = new Elementary_Sanitize();
            $payload = $sanitize->sanitize_array($_POST['payload']);
            $payload = $validate->validate_array($payload);


            // error_log( print_r( $payload, true ) );

            /* Payload Validation */

            // $wordpress_functions->elementary_log('$payload: '.$payload['atomStore']);
    	}
    	else {
    		$wordpress_functions->elementary_log('Payload not available during save.');
    	}



        if ( $post_id && is_numeric( $post_id ) ) {

            $post_data = array(
                'ID'            => $post_id,
                'post_title'    => wp_strip_all_tags( $post_title ),
                'post_status'   => 'publish',
                'post_type'     => 'pauple_element',
            );

            $insert_post = wp_insert_post( $post_data );

            $payload_keys = array_keys( $payload );
            foreach ( $payload_keys as $option ) {
                update_post_meta( $post_id, '_' . $option, $payload[$option] );
            }
            update_post_meta( $post_id, '_appDBInfo', $app_db_info );

            if ( ! $insert_post ) {
                $wordpress_functions->elementary_log( 'wp_insert_post() failed.' );
                $response = array( 'message'	=> 'Element could not be saved' );
                wp_send_json_error( $response );
            }
            else {
                $response = array( 'message'	=> 'Element with ID ' . $post_id . ' saved successfully.' );
                wp_send_json_success( $response );
            }

        }
        else {
            $wordpress_functions->elementary_log( 'Invalid Post ID. Unable to save the element with ID ' . $post_id );
        }
    }
    else {
        $response = array( 'message'	=> 'You do not have sufficient permissions to save.' );
        wp_send_json_error( $response );
    }
}
