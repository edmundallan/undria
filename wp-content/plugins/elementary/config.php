<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementary_Config' ) ) :
	class Elementary_Config {

	    public function get_element_id( $post_id ) {
	        return 'elementary-id-' . $post_id;
	    }

	    public function get_wrapper( $post_id ) {
			$element_id = $this->get_element_id( $post_id );
	        return '<div id= "' . $element_id . '" class="elementary-wrapper">';
	    }

	}
endif;
