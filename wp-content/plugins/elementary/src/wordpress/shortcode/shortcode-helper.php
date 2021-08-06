<?php

// // check the current post for the existence of a short code
// function has_shortcode($shortcode = '') {
//
//     $post_to_check = get_post(get_the_ID());
//
//     // false because we have to search through the post content first
//     $found = false;
//
//     // if no short code was provided, return false
//     if (!$shortcode) {
//         return $found;
//     }
//     // check the post content for the short code
//     if ( stripos($post_to_check->post_content, '[' . $shortcode) !== false ) {
//         // we have found the short code
//         $found = true;
//     }
//
//     // return our final results
//     return $found;
// }


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementary_Shortcode_Helper' ) ) :
    Class Elementary_Shortcode_Helper {



        public function get_frontend_css( $post_id ) {
            $frontend_css = array();
            $css_store = get_post_meta( $post_id, '_cssStore', true );
            if ( $css_store && ( is_array( $css_store ) ) ) {
                if ( array_key_exists( 'frontendCSS', $css_store ) ) {
                    $frontend_css = $css_store['frontendCSS'];
                }
                else {
                    global $wordpress_functions;
                    $wordpress_functions->elementary_log('frontendCSS empty for post_id: ' . $post_id );
                }
            }
            return $frontend_css;
        }


        public function get_css_obj( $post_id ) {
            $frontend_css = array();
            $css_store = get_post_meta( $post_id, '_cssStore', true );
            if ( $css_store && ( is_array( $css_store ) ) ) {
                if ( array_key_exists( 'cssObj', $css_store ) ) {
                    $frontend_css = $css_store['cssObj'];
                }
                else {
                    global $wordpress_functions;
                    $wordpress_functions->elementary_log('cssObj empty for post_id: ' . $post_id );
                }
            }
            return $frontend_css;
        }


        public function get_atoms_list( $post_id ) {
            $atoms_list = array();
            $atom_store = get_post_meta( $post_id, '_atomStore', true );
            if ( $atom_store && ( is_array( $atom_store ) ) ) {
                if ( array_key_exists( 'storeAtoms', $atom_store ) ) {
                    $atoms_list = $atom_store['storeAtoms'];
                }
                else {
                    global $wordpress_functions;
                    $wordpress_functions->elementary_log('Atoms list is empty for post_id: ' . $post_id );
                }
            }
            return $atoms_list;
        }


        public function get_atom_type_array( $content_settings_store ) {
            $atom_type_array = array();
            if ( $content_settings_store && ( is_array( $content_settings_store ) ) ) {
                if ( array_key_exists( 'atomTypeArray', $content_settings_store ) ) {
                    $atom_type_array = $content_settings_store['atomTypeArray'];
                }
                else {
                    global $wordpress_functions;
                    $wordpress_functions->elementary_log('Atom type array is empty' );
                }
            }
            return $atom_type_array;
        }


        public function get_content_settings( $content_settings_store ) {
            $content_settings = $filter_list = $post_type = $post_type_criteria = $post_type_value = $criteria = $taxonomy = $taxonomy_criteria = $taxonomy_value = $term = $term_criteria = $term_value = $field = $comparison = $post_id = $date = $date_criteria = $date_value = $sorting = array();

            $index = 0;

        	global $wordpress_functions;
            $init_condition = !( $content_settings_store );
            $load_condition = $content_settings_store && ( is_array( $content_settings_store ) );

            if( $init_condition ) {
                // Do Nothing..
            }
            elseif ( $load_condition ) {
                if ( array_key_exists( 'fieldsList', $content_settings_store ) ) {
                    $field_value_array = $content_settings_store['fieldsList'];
                    $content_settings['field_value'] = $field_value_array;
                }
                else {
                    $wordpress_functions->elementary_log('Field value array is empty');
                }

                if ( array_key_exists( 'date', $content_settings_store ) ) {
                    $date = $content_settings_store['date'];
        			if ( $date && ( is_array( $date ) ) ) {
        				if ( array_key_exists( 'criteria', $date ) ) {
        					$date_criteria = $date['criteria'];
        				}
        				if ( array_key_exists( 'value', $date ) ) {
        					$date_value = $date['value'];
        				}
        			}
                    $content_settings['date'] = $date;
                }

                if ( array_key_exists( 'sorting', $content_settings_store ) ) {
                    $sorting = $content_settings_store['sorting'];
                    $content_settings['sorting'] = $sorting;
                }

                if ( array_key_exists( 'filtersList', $content_settings_store ) ) {
                    $filter_list = $content_settings_store['filtersList'];
                    $content_settings['filtersList'] = $filter_list;
                    if ( array_key_exists( $index, $filter_list ) ) {

                        if ( array_key_exists( 'post_type', $filter_list[$index] ) ) {
                            $post_type = $filter_list[$index]['post_type'];
                            if ( array_key_exists( 'criteria', $post_type ) ) {
                                $post_type_criteria = $post_type['criteria'];
                            }
                            if ( array_key_exists( 'value', $post_type ) ) {
                                $post_type_value = $post_type['value'];
                            }
                            if ( $post_type_criteria === 'any' ) {
                                $post_type_value = elementary_get_all_post_types();
                            }
                            $post_id_array = $wordpress_functions->get_post_ids_from_post_type( $post_type_value );
                            $post_id_based_on_date = $wordpress_functions->get_post_ids_based_on_date( $post_type_value, $date_criteria, $date_value );
                            if ( $post_id_based_on_date ) {
                                $post_id_array = array_merge( array_intersect( $post_id_array, $post_id_based_on_date ) );
                            }
                        }

                        if ( array_key_exists( 'criteria', $filter_list[$index] ) ) {
                            $criteria = $filter_list[$index]['criteria'];
        					if ( !$criteria ) {
        						$criteria = 'field';
        					}
                            if ( $criteria === 'field') {
                                if ( array_key_exists( 'field', $filter_list[$index] ) ) {
                                    $field = $filter_list[$index]['field'];
                                }
                                if ( array_key_exists( 'comparison', $filter_list[$index] ) ) {
                                    $comparison = $filter_list[$index]['comparison'];
                                }
                                if ( array_key_exists( 'post_id', $filter_list[$index] ) ) {
                                    $post_id = $filter_list[$index]['post_id'];
                                }
                				$post_id_based_on_comparison = $wordpress_functions->get_post_ids_based_on_comparison( $post_id_array, $comparison, $post_id );
                                $post_id_based_on_date = $wordpress_functions->get_post_ids_based_on_date( $post_type_value, $date_criteria, $date_value );
                                if ( $post_id_based_on_comparison && $post_id_based_on_date ) {
                                    $post_id_array = array_merge( array_intersect( $post_id_based_on_comparison, $post_id_based_on_date ) );
                                }
                                else {
                                    $post_id_array = $post_id_based_on_comparison;
                                }
                            }
                            elseif ( $criteria === 'taxonomy') {
                                if ( array_key_exists( 'taxonomy', $filter_list[$index] ) ) {
                                    $taxonomy = $filter_list[$index]['taxonomy'];
        							if ( $taxonomy && ( is_array( $taxonomy ) ) ) {
        	                            if ( array_key_exists( 'criteria', $taxonomy ) ) {
        	                                $taxonomy_criteria = $taxonomy['criteria'];
        	                            }
        	                            if ( array_key_exists( 'value', $taxonomy ) ) {
        	                                $taxonomy_value = $taxonomy['value'];
        	                            }
                                        if ( $taxonomy_criteria === 'any' ) {
                        					$taxonomy_value = elementary_get_taxonomy_array( $post_type_value );
                        				}
        							}
                                }
                                if ( array_key_exists( 'terms', $filter_list[$index] ) ) {
                                    $term = $filter_list[$index]['terms'];
        							if ( $term && ( is_array( $term ) ) ) {
        	                            if ( array_key_exists( 'criteria', $term ) ) {
        	                                $term_criteria = $term['criteria'];
        	                            }
        	                            if ( array_key_exists( 'value', $term ) ) {
        	                                $term_value = $term['value'];
        	                            }
                                        if ( $term['criteria'] === 'any' ) {
                        					$term_value = elementary_get_terms_array( $taxonomy_value );
                        				}
        							}
                                }
                                $taxonomy = $wordpress_functions->get_taxonomy($post_type_value, $taxonomy_criteria, $taxonomy_value );
                                $terms = $wordpress_functions->get_terms($taxonomy, $term_criteria, $term_value );
                                if ( $term_criteria === 'any') {
            						if ( $taxonomy_criteria === 'any') {
            							$post_id_based_on_term = $wordpress_functions->get_post_ids_from_post_type( $post_type );
            						}
            						else {
            							$post_id_based_on_term = $wordpress_functions->get_post_ids_from_term( $term_criteria, $terms );
            						}
            					}
            					else {
            						$post_id_based_on_term = $wordpress_functions->get_post_ids_from_term( $term_criteria, $term_value );
            					}
                                $post_id_based_on_date = $wordpress_functions->get_post_ids_based_on_date( $post_type_value, $date_criteria, $date_value );
                                if ( $post_id_based_on_term && $post_id_based_on_date ) {
                                    $post_id_array = array_merge( array_intersect( $post_id_based_on_term, $post_id_based_on_date ) );
                                }
                                else {
                                    $post_id_array = $post_id_based_on_term;
                                }
                            }
                        }
                    }
                }
                else {
                    $wordpress_functions->elementary_log('Filters List array is empty');
                }
                $post_id_array = $wordpress_functions->sort_post_id( $post_id_array, $sorting );
                $content_settings['post_id_array'] = $post_id_array;
            }
            else { // possible error
                $wordpress_functions->elementary_log('Invalid Content Settings Store value');
            }
            return $content_settings;
        }


        public function get_content_settings_store( $post_id ) {
            $content_settings_store = get_post_meta( $post_id, '_contentSettingsStore', true );
            return $content_settings_store;
        }


        public function get_group_settings_store( $post_id ) {
            $group_settings_store = get_post_meta( $post_id, '_groupSettingsStore', true );
            return $group_settings_store;
        }


        public function get_carousel_store( $post_id ) {
            $carousel_store = get_post_meta( $post_id, '_carouselStore', true );
            return $carousel_store;
        }


        public function get_media_data() {

            $media_data = array(
                '0' => array(
                        'name' => 'general',
                        'default_width' => '1280',
                    ),
                '1' => array(
                        'name' => 'desktop',
                        'first_operator' => '>',
                        'first_operand' => '1024',
                        'default_width' => '1200',
                    ),
                '2' => array
                    (
                        'name' => 'tablet',
                        'first_operator' => '<=',
                        'first_operand' => '1024',
                        'second_operator' => '>',
                        'second_operand' => '600',
                        'default_width' => '768',
                    ),
                '3' => array
                    (
                        'name' => 'mobile',
                        'first_operator' => '<=',
                        'first_operand' => '600',
                        'default_width' => '420',
                    )
            );
            return $media_data;
        }


        public function get_media_queries( $media_data ) {
            $media_query_array = array();
            foreach ( array_keys( $media_data ) as $index ) {
                $media_query = $first_operator = $first_operand = $second_operator = $second_operand = '';
                foreach ( array_keys( $media_data[ $index ] ) as $key => $value ) {
                    if ( $value == 'first_operator' ) {
                        $first_operator = $media_data[ $index ]['first_operator'];
                    }
                    elseif ( $value == 'first_operand' ) {
                        $first_operand = $media_data[ $index ]['first_operand'];
                        $media_query .= '(';
                        if ( $first_operator == '>' ) {
                            $media_query .= 'min-width:';
                            $media_query .= ( $first_operand + 1 ) . 'px';
                        }
                        elseif ( $first_operator == '>=' ) {
                            $media_query .= 'min-width:';
                            $media_query .= $first_operand . 'px';
                        }
                        elseif ( $first_operator == '<' ) {
                            $media_query .= 'max-width:';
                            $media_query .= ( $first_operand - 1 ) . 'px';
                        }
                        elseif ( $first_operator == '<=' ) {
                            $media_query .= 'max-width:';
                            $media_query .= $first_operand . 'px';
                        }
                        else {
                            $wordpress_functions->elementary_log('Unidentified operator: ' . $first_operator );
                        }
                        $media_query .= ')';
                    }
                    elseif ( $value == 'second_operator' ) {
                        $second_operator = $media_data[ $index ]['second_operator'];
                    }
                    elseif ( $value == 'second_operand' ) {
                        $second_operand = $media_data[ $index ]['second_operand'];
                        $media_query .= ' and (';
                        if ( $second_operator == '>' ) {
                            $media_query .= 'min-width:';
                            $media_query .= ( $second_operand + 1 ) . 'px';
                        }
                        elseif ( $second_operator == '>=' ) {
                            $media_query .= 'min-width:';
                            $media_query .= $second_operand . 'px';
                        }
                        elseif ( $second_operator == '<' ) {
                            $media_query .= 'max-width:';
                            $media_query .= ( $second_operand - 1 ) . 'px';
                        }
                        elseif ( $second_operator == '<=' ) {
                            $media_query .= 'max-width:';
                            $media_query .= $second_operand . 'px';
                        }
                        else {
                            $wordpress_functions->elementary_log('Unidentified operator: ' . $second_operator );
                        }
                        $media_query .= ')';
                    }
                }
                $media_query_array[] = $media_query;
            }
            return $media_query_array;
        }


        public function construct_css( $css_data, $media_queries ) {
            $css_output = $css_content = '';
            $css_output .= '<script type="text/javascript">(function($){$(document).ready(function(){';
            $css_output .= '$(window).load(function() {';
            $css_output .= 'console.info($(".base-element").length + " elements rendered!");';

            if ( is_array( $css_data ) ) {
                foreach ( array_keys( $css_data ) as $media_id ) {
                    if ( $media_id != 0 ) {
                        $css_output .= 'if (window.matchMedia("';
                        $css_output .= $media_queries[ $media_id ];
                        $css_output .= '").matches)';
                        $css_output .= $wordpress_functions->elementary_get_open_tag();
                    }
                    $media_count = count( $css_data[ $media_id ] );
                    for ( $i = 1; $i <= $media_count; $i++ ) {
                        if ( array_key_exists( $i, $css_data[ $media_id ] ) ) {
                            if ( array_key_exists( 'selector', $css_data[ $media_id ][ $i ] ) ) {
                                $selector = $css_data[ $media_id ][ $i ]['selector'];
                            }
                            else {
                                $wordpress_functions->elementary_log('Selector doesn\'t exist at media-id: ' . $media_id . ' for element number: ' . $i . '' );
                            }

                            if ( array_key_exists( 'css_content', $css_data[ $media_id ][ $i ] ) ) {
                                $css_content = json_encode( $css_data[ $media_id ][ $i ]['css_content'] );
                            }
                            else {
                                $wordpress_functions->elementary_log('CSS content doesn\'t exist at media-id: ' . $media_id . ' for element number: ' . $i . '' );
                            }

                            $css_output .= '$("' . $selector . '").css(' . $css_content . ');';
                        }
                    }
                    if ( $media_id != 0 ) {
                        $css_output .= $wordpress_functions->elementary_get_close_tag();
                    }
                }
            }
            else {
                $wordpress_functions->elementary_log('Invalid CSS Data' );
            }



            $css_output .= '});';
            $css_output .= '})})(jQuery);</script><!-- Elementary CSS Output -->';
            return $css_output;
        }


        public function render_element( $payload ) {

            $elementary_config = new Elementary_Config();

            $post_id  = $payload['post_id'];
            $element_id = $elementary_config->get_element_id( $post_id );
            $cssObj = $this->get_css_obj($post_id);
            $atoms_list = $payload['atoms_list'];
            $carousel_store = $payload['carousel_store'];
            $content_list = $payload['content_list'];
            $content_settings_store = $payload['content_settings_store'];
            $group_settings_store = $payload['group_settings_store'];
            $path = $_SERVER['REQUEST_URI'];
            $uri = $_SERVER['HTTP_HOST'] . $path;
            $page = get_query_var('page');

            $render_callback = array(
                'postID'                => $post_id,
                'elementID'             => $element_id,
                'cssObj'                => $cssObj,
                'atomsList'             => $atoms_list,
                'carouselStore'         => $carousel_store,
                'contentList'           => $content_list,
                'contentSettingsStore'  => $content_settings_store,
                'groupSettingsStore'    => $group_settings_store,
                'uri'                   => $uri,
                'page'                  => $page,
            );

            $output = '<script type="text/javascript">';
            $output .= 'var AppPayload = ' . json_encode( $render_callback ) . ';';
            $output .= '</script>';
            $output .= '<script type="text/javascript" src="' . ELEMENTARY_PLUGIN_PATH . '/frontend.bundle.js"></script>';
            // $output .= '<script type="text/javascript" src="' . ELEMENTARY_PLUGIN_PATH . '/vendor/velocity.min.js"></script>';

            // To prevent card-behaviour to be called twice when  more than 1 collection is present in the same page.
            if(!isset($GLOBALS['NUM_OF_ELEMENTARY_COLLECTIONS'])){
                global $NUM_OF_ELEMENTARY_COLLECTIONS;
                $GLOBALS['NUM_OF_ELEMENTARY_COLLECTIONS'] = 1;
                $output .= '<script type="text/javascript" src="' . ELEMENTARY_PLUGIN_PATH . '/assets/javascript/card-behaviour.js"></script>';
            }

            $output .= '<!-- Elementary Render HTML -->';
            return $output;
        }

    }


endif;
