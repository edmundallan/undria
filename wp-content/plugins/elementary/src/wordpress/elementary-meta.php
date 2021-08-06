<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementary_Meta' ) ) :
	class Elementary_Meta {
	    public function is_woocommerce_installed() {
            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        		return true;
        	} else {
        		return false;
        	}
        }
	}
endif;

$elementary_meta = new Elementary_Meta();

if ( $elementary_meta->is_woocommerce_installed() ) {

    // add_filter('elementary_meta_fields', 'elementary_add_custom_meta_fields');

    function elementary_add_custom_meta_fields ( $meta_fields ) {
        // the $meta_fields parameter is an array of all meta fields from the elementary_post_meta() function
    	$custom_meta_fields = array(
    		'field1',
    		'field2'
    	);
    	return array_merge( $meta_fields, $custom_meta_fields );
    }


    add_filter( 'woocommerce_prevent_admin_access', 'elementary_allow_backend_access_to_demo_user' );

    function elementary_allow_backend_access_to_demo_user( $prevent_access ) {
        /*
    		Description: Modify the Default behaviour of WooCommerce & allow access to the user with `manage_element` capability to access the admin backend
            Reference: https://wordpress.org/support/topic/woocommerce-ovverides-my-dashboard#post-6938121
    	*/
		if ( current_user_can( 'manage_element' ) ) {
			$prevent_access = false;
		}
		return $prevent_access;
	}
}


function elementary_custom_posts_array ( $posts_array ) {
    // the $posts_array parameter is an array of taxonomies from the elementary_get_all_post_types() function

    $exclude = array( 'attachment', 'page', 'pauple_element' );
	$posts_array = array_diff( $posts_array, $exclude );
	$posts_array = array_values( $posts_array );
    return $posts_array;
}

add_filter('elementary_posts_array', 'elementary_custom_posts_array');


function elementary_custom_taxonomy_array ( $taxonomy_array ) {
    // the $taxonomy_array parameter is an array of taxonomies from the taxonomy_array() function
	$custom_tax = array(
		'Any'
	);
	return array_merge( $custom_tax, $taxonomy_array );
}

// add_filter('elementary_taxonomy_array', 'elementary_custom_taxonomy_array');


function elementary_custom_terms_array ( $terms_array ) {
    // the $terms_array parameter is an array of terms from the elementary_terms_array() function
	$custom_term = array(
		'Any'
	);
	return array_merge( $custom_term, $terms_array );
}

// add_filter('elementary_terms_array', 'elementary_custom_terms_array');




function is_elementary_test_mode_enabled() {
	if ( defined( 'ELEMENTARY_TESTS' ) && ELEMENTARY_TESTS ) {
		return true;
	} else {
		return false;
	}
}

// Enable Shortcode usage in widgets
// add_filter('widget_text', 'do_shortcode');
