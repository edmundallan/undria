<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementary_Localize_Scripts' ) ) :
	class Elementary_Localize_Scripts {

        public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'localize_scripts' ) );
        }

        public function localize_scripts() {

		    wp_enqueue_script( 'elementary-ajax-object', ELEMENTARY_PLUGIN_JS_DIR . '/elementary-ajax-object.js', array(), ELEMENTARY_PLUGIN_VERSION, true);

		    $elementary_ajax_object = array(
		        'elementary_save_to_database'   => wp_create_nonce( 'elementary_save_to_database' ),
		        'elementary_reset_database'     => wp_create_nonce( 'elementary_reset_database' ),
		        'elementary_fetch_db_data'      => wp_create_nonce( 'elementary_fetch_db_data' ),
		        'elementary_user_interaction'   => wp_create_nonce( 'elementary_user_interaction' ),
		    );

		    wp_localize_script( 'elementary-ajax-object', 'elementaryAjaxObject', $elementary_ajax_object );

		}
    }
endif;

new Elementary_Localize_Scripts();
