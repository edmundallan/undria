<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_elementary_reset_database', 'elementary_reset_database' );

function elementary_reset_database() {

	if ( ( !wp_verify_nonce( $_POST['nonce'], "elementary_reset_database") )
	&& ( !is_elementary_test_mode_enabled() ) ) {
		exit();
	}

    if ( current_user_can( 'manage_element' ) ) {

        if ( array_key_exists( 'postID', $_POST ) ) {
    		$post_id = intval( $_POST['postID'] );
    	}
    	else {
    		$wordpress_functions->elementary_log('Post ID not available during reset.');
    	}


        if ( $post_id && is_numeric( $post_id ) ) {

            $meta_keys = elementary_get_matching_keys( $post_id );

            foreach ( $meta_keys as $key ) {
                $key = sanitize_text_field($key);
                update_post_meta( $post_id, '_' . $key, '' );
            }
            $delete_meta = update_post_meta( $post_id, '_appDBInfo', '' );


            if ( ! $delete_meta ) {
                $response = array( 'message'	=> 'Post could not be reset' );
                wp_send_json_error( $response );
            }
            else {
                $response = array( 'message'	=> 'Post with ID ' . $post_id . ' reset successfully.' );
                wp_send_json_success( $response );
            }

        }
        else {
            $wordpress_functions->elementary_log( 'Invalid Post ID. Unable to reset the element with ID ' . $post_id );
        }

    }

}
