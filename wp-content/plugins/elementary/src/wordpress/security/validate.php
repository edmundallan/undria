<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pauple_elementary_is_string_or_num( $input, $fallback = '' ){
    if ( is_numeric( $input ) || is_string( $input ) ) {
        return $input;
    }
    else {
        return $fallback;
    }
}


if ( ! class_exists( 'Elementary_Validate' ) ) :
	class Elementary_Validate {

		public function whitelist_check( $input, $safe_values, $fallback = '' ) {
			$safe_values = (array) $safe_values;	// Typecast to Array
			if ( in_array( $input, $safe_values, true ) ) {  // `true` enables strict type checking
			    return $input;
			}
			else {
			    return $fallback;
			}
		}

		public function numeric_check( $input, $fallback = '' ) {
			if ( is_numeric( $input ) ) {
			    return $input;
			}
			else {
			    return $fallback;
			}
		}


        public function array_map_r( $func, $arr ){
            $newArr = array();

            foreach( $arr as $key => $value )
            {
                $newArr[ $key ] = ( is_array( $value ) ? $this->array_map_r( $func, $value ) : ( is_array($func) ? call_user_func_array($func, $value) : $func( $value ) ) );
            }

            return $newArr;
        }

        public function validate_array($array){
            $sanitized_array = $this->array_map_r( 'pauple_elementary_is_string_or_num', $array);
            return $sanitized_array;
        }

		public function validate( $context, $input ) {
			$safe_values = $fallback = $output = '';
			if ( $context && $input ) {
				if ( ( $context === 'post-id' ) || ( $context === 'attachment-number' ) ) {
					$output = $this->numeric_check( $input );
				}
				else {
					if ( $context === 'post-type' ) {
						$fallback = 'post';
						// $post_types = post_type_array( $exclude = array( 'attachment', 'page', 'element' ) );
						// $post_types = (array) $post_types;	// Typecast to Array
						// $safe_values = array_merge( array('Any'), $post_types );
					}
					elseif ( $context === 'criteria' ) {
						$fallback = 'field';
						$safe_values = array( 'field', 'taxonomy' );
					}
					// elseif ( $context === 'taxonomy' ) {
					// 	$fallback = '';
					// 	$safe_values = array( '' );
					// }
					// elseif ( $context === 'term' ) {
					// 	$fallback = '';
					// 	$safe_values = array( '' );
					// }
					elseif ( $context === 'sorting' ) {
						$fallback = 'descending';
						$safe_values = array( 'ascending', 'descending' );
					}
					elseif ( $context === 'field' ) {
						$fallback = 'post-id';
						$safe_values = array( 'post-id' );
					}
					elseif ( $context === 'comparison' ) {
						$fallback = 'equal-to';
						$safe_values = array( 'equal-to', 'lesser-than', 'greater-than' );
					}
					else {
						$wordpress_functions->elementary_log( 'Invalid context: ' . $context . ' supplied for Validate function');
					}
					$output = $this->whitelist_check( $input, $safe_values, $fallback );
				}
			}
			else {
				$wordpress_functions->elementary_log( 'Empty Context or Input is supplied for Validate function');
			}
			return $output;
		}
	}
endif;
