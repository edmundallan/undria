<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementary_Sanitize' ) ) :
	class Elementary_Sanitize {
        
	    public function sanitize_text( $string ) {
	        $sanitized_string = sanitize_text_field( $string );
	        return $sanitized_string;
	    }

	    // public function sanitize_array( $array ) {
	    //     $sanitized_array = array();
        //
		// 	if ( is_array( $array ) ) {
		//         foreach ( $array as $string ) {
		//             $sanitized_array[] = $this->sanitize_array( $string );
		//         }
		// 	}
        //     else{
        //         $sanitized_array[] = $this->sanitize_text( $array );
        //     }
	    //     return $sanitized_array;
	    // }

        public function el_sanitize_array_keys($array){
            $sanitized_array = $this->array_map_r( 'sanitize_key', $array);
            return $sanitized_array;
        }


        public function sanitize_array( $array ) {
	        $sanitized_array = $this->array_map_r( 'sanitize_text_field', $array);
            return $sanitized_array;
	    }


        public function array_map_r( $func, $arr ){
            $newArr = array();

            foreach( $arr as $key => $value )
            {
                $newArr[ $key ] = ( is_array( $value ) ? $this->array_map_r( $func, $value ) : ( is_array($func) ? call_user_func_array($func, $value) : $func( $value ) ) );
            }

            return $newArr;
        }

	}
endif;
