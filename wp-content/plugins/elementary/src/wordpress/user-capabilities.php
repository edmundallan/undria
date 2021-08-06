<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementary_User_Capabilities' ) ) :
	class Elementary_User_Capabilities {

        public function __construct() {
            add_action( 'admin_init', array( $this, 'add_role_caps' ) );
        }

        public function add_role_caps() {

			$roles = array( 'editor', 'administrator' );

			foreach( $roles as $the_role ) {

		        $role = get_role( $the_role );

		        $caps = array(
		            'read_element',
		            'read_private_elements',
		            'edit_element',
		            'edit_elements',
		            'edit_others_elements',
		            'edit_published_elements',
		            'publish_elements',
		            'manage_element',
		            'delete_others_elements',
		            'delete_private_elements',
		            'delete_published_elements',
		        );

		        foreach ( $caps as $cap ) {
		            $role->add_cap( $cap );
		        }

			}
		}
    }
endif;

new Elementary_User_Capabilities();
