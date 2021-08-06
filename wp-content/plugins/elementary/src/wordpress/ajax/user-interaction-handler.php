<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_elementary_user_interaction', 'elementary_user_interaction' );

function elementary_user_interaction() {
    global $wordpress_functions;

	if ( ( !wp_verify_nonce( $_POST['nonce'], "elementary_user_interaction") )
	&& ( !is_elementary_test_mode_enabled() ) ) {
		exit();
	}

    if ( current_user_can( 'manage_element' ) ) {

		$content_settings = $context = $filter_list = $post_type = $post_type_criteria = $post_type_value = $criteria = $taxonomy = $taxonomy_criteria = $taxonomy_value = $term = $term_criteria = $term_value = $field = $comparison = $post_id = $date = $date_criteria = $date_value = $sorting = $fields_list = $field = $atom_type_array = $post_id_based_on_date = $post_id_based_on_term = $post_id_based_on_comparison = array();

		$output['filtersListTaxValue'] = array();
		$output['filtersListTermValue'] = array();

	    $shortcodes = new Elementary_Shortcodes();
	    $output = array();
        $sanitize = new Elementary_Sanitize();
		$content_settings = $sanitize->sanitize_array($_POST['payload']);


		if ( $content_settings && ( is_array( $content_settings ) ) ) {
	        if ( array_key_exists( 'context', $content_settings ) ) {
	            $context = $content_settings['context'];
			}
	        if ( array_key_exists( 'filtersList', $content_settings ) ) {
	            $filter_list = $content_settings['filtersList'];
	            foreach ( array_keys( $filter_list ) as $index ) {
	                if ( array_key_exists( 'post_type', $filter_list[$index] ) ) {
	                    $post_type = $filter_list[$index]['post_type'];
	                    if ( array_key_exists( 'criteria', $filter_list[$index]['post_type'] ) ) {
	                        $post_type_criteria = $filter_list[$index]['post_type']['criteria'];
	                        // print_array($post_type_criteria);
	                    }
	                    if ( array_key_exists( 'value', $filter_list[$index]['post_type'] ) ) {
	                        $post_type_value = $filter_list[$index]['post_type']['value'];
	                        // print_array($post_type_value);
	                    }
	                }
	                if ( array_key_exists( 'criteria', $filter_list[$index] ) ) {
	                    $criteria = $filter_list[$index]['criteria'];
						if ( !$criteria ) {
							$criteria = 'field';
						}
						// print_array($criteria);
	                    if ( $criteria === 'field') {
	                        if ( array_key_exists( 'field', $filter_list[$index] ) ) {
	                            $field = $filter_list[$index]['field'];
	                            // print_array($field);
	                        }
	                        if ( array_key_exists( 'comparison', $filter_list[$index] ) ) {
	                            $comparison = $filter_list[$index]['comparison'];
	                            // print_array($comparison);
	                        }
	                        if ( array_key_exists( 'post_id', $filter_list[$index] ) ) {
	                            $post_id = $filter_list[$index]['post_id'];
	                            // print_array($post_id);
	                        }
	                    }
	                    elseif ( $criteria === 'taxonomy') {
	                        if ( array_key_exists( 'taxonomy', $filter_list[$index] ) ) {
	                            $taxonomy = $filter_list[$index]['taxonomy'];
								if ( $taxonomy && ( is_array( $taxonomy ) ) ) {
		                            if ( array_key_exists( 'criteria', $taxonomy ) ) {
		                                $taxonomy_criteria = $taxonomy['criteria'];
		                                // print_array($taxonomy_criteria);
		                            }
		                            if ( array_key_exists( 'value', $taxonomy ) ) {
		                                $taxonomy_value = $taxonomy['value'];
		                                // print_array($taxonomy_value);
		                            }
								}
	                        }
	                        if ( array_key_exists( 'terms', $filter_list[$index] ) ) {
	                            $term = $filter_list[$index]['terms'];
								if ( $taxonomy && ( is_array( $taxonomy ) ) ) {
		                            if ( array_key_exists( 'criteria', $term ) ) {
		                                $term_criteria = $term['criteria'];
		                                // print_array($term_criteria);
		                            }
		                            if ( array_key_exists( 'value', $term ) ) {
		                                $term_value = $term['value'];
		                                // print_array($term_value);
		                            }
								}
	                        }
	                    }
	                    else {
	                        $wordpress_functions->elementary_log('Invalid Criteria');
	                    }
	                }
	            }
	        }
			if ( array_key_exists( 'date', $content_settings ) ) {
	            $date = $content_settings['date'];
				if ( $date && ( is_array( $date ) ) ) {
					if ( array_key_exists( 'criteria', $date ) ) {
						$date_criteria = $date['criteria'];
					}
					if ( array_key_exists( 'value', $date ) ) {
						$date_value = $date['value'];
					}
				}
	        }
	        if ( array_key_exists( 'sorting', $content_settings ) ) {
	            $sorting = $content_settings['sorting'];
	            // print_array($sorting);
	        }
	        if ( array_key_exists( 'fieldsList', $content_settings ) ) {
	            $fields_list = $content_settings['fieldsList'];
	        }
	    }
		else {
			$wordpress_functions->elementary_log('Error');
		}

		if ( array_key_exists( 'atomTypeArray', $content_settings ) ) {
			$atom_type_array = $content_settings['atomTypeArray'];
		}

		global $wordpress_functions;

		$post_type_obj['criteria'] = $wordpress_functions->get_criteria();
		$post_type_obj['value'] = elementary_get_all_post_types();

		$post_type = $wordpress_functions->get_post_types( $post_type_criteria, $post_type_value );
		$post_id_array = $wordpress_functions->get_post_ids_from_post_type( $post_type );

		$filter_obj = $wordpress_functions->get_filter_criteria();

		$sorting_options = $wordpress_functions->get_sorting_options();

		if ( ( !$wordpress_functions->functionally_empty( $context ) ) ) {

			$output['context'] = $context;

			if ( $context === 'initial' ) {
				$output['filtersListOL'][0]['post_type'] = $post_type_obj;
			}
			elseif ( ( $context === 'post_type_criteria' ) || ( $context === 'post_type_value' )  || ( $context === 'criteria' ) ) {
				$output['filtersListOL'][0]['post_type'] = $post_type_obj;
				$output['fieldsListOL'] = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
				$output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
			}
			if ( $criteria === 'field') {
			    // $output['filtersListOL'][0]['field'] = $wordpress_functions->get_fields();
				if ( $context === 'field' ) {
				    $output['filtersListOL'][0]['comparison'] = $wordpress_functions->get_comparison();
				}
				elseif ( $context === 'comparison' ) {
				    $output['filtersListOL'][0]['post_id'] = $post_id_array;
					$output['fieldsListOL'] = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
				}
				elseif ( $context === 'post_id' ) {

					$post_id_based_on_comparison = $wordpress_functions->get_post_ids_based_on_comparison( $post_id_array, $comparison, $post_id );
					$post_id_based_on_date = $wordpress_functions->get_post_ids_based_on_date( $post_type, $date_criteria, $date_value );
					$post_id_array = array_merge( array_intersect( $post_id_based_on_comparison, $post_id_based_on_date ) );

					$post_id_array = $wordpress_functions->sort_post_id( $post_id_array, $sorting );
					$output['fieldsListOL'] = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
					$output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
				}
				elseif ( $context === 'date' ) {

					$post_id_array = $wordpress_functions->get_post_ids_based_on_comparison( $post_id_array, $comparison, $post_id );

					$post_id_array = $wordpress_functions->sort_post_id( $post_id_array, $sorting );
					$output['fieldsListOL'] = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
					$output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
				}
				elseif ( ( $context === 'date-value' ) || ( $context === 'date-value2' ) ) {
					$post_id_based_on_comparison = $wordpress_functions->get_post_ids_based_on_comparison( $post_id_array, $comparison, $post_id );
					$post_id_based_on_date = $wordpress_functions->get_post_ids_based_on_date( $post_type, $date_criteria, $date_value );
					$post_id_array = array_merge( array_intersect( $post_id_based_on_comparison, $post_id_based_on_date ) );

					$post_id_array = $wordpress_functions->sort_post_id( $post_id_array, $sorting );
					$output['fieldsListOL'] = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
					$output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
				}
				elseif ( $context === 'sorting' ) {
				    $output['filtersListOL'][0]['sorting'] = $sorting;
					$post_id_based_on_comparison = $wordpress_functions->get_post_ids_based_on_comparison( $post_id_array, $comparison, $post_id );
					$post_id_based_on_date = $wordpress_functions->get_post_ids_based_on_date( $post_type, $date_criteria, $date_value );
					$post_id_array = array_merge( array_intersect( $post_id_based_on_comparison, $post_id_based_on_date ) );

					$post_id_array = $wordpress_functions->sort_post_id( $post_id_array, $sorting );
					$output['fieldsListOL'] = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
					$output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
				}
				elseif ( ( substr( $context, 0, 5 ) === 'field' ) && ( array_pop( ( explode( "_", $context ) ) ) === 'type' ) ) {
					$field_key = array_shift( ( explode( "_", $context ) ) );		// May be 'field1', 'field2' etc..
				    if ( array_key_exists( $field_key, $atom_type_array ) ) {
						$field_type = $atom_type_array[$field_key];		// May be 'text' or 'link' or 'image'.
					}
				    else {
				        $wordpress_functions->elementary_log('Invalid Field Key: ' . $field_key );
				    }
				    $field_value_array = $wordpress_functions->get_fields_list( $field_type, $post_id_array );
					if ( array_key_exists( 0, $field_value_array ) ) {
						$output['fieldsListOL'][$field_key] = $field_value_array[0];
					}
				}
				elseif ( ( substr( $context, 0, 5 ) === 'field' ) && ( array_pop( ( explode( "_", $context ) ) ) === 'value' ) ) {
				    $output['filtersListOL'][0]['sorting'] = $sorting;
					if ( $comparison && $post_id ) {
						$post_id_array = $wordpress_functions->get_post_ids_based_on_comparison( $post_id_array, $comparison, $post_id );
					}
					if ( $sorting ) {
						$post_id_array = $wordpress_functions->sort_post_id( $post_id_array, $sorting );
					}
					$output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
				}
			}
			elseif ( $criteria === 'taxonomy') {
				$taxonomy_obj['criteria'] = $wordpress_functions->get_criteria();
				$taxonomy_obj['value'] = elementary_get_taxonomy_array( $post_type );
			    $output['filtersListOL'][0]['taxonomy'] = $taxonomy_obj;

				if (
					( $context === 'terms_criteria' ) ||
					( $context === 'terms_value' ) ||
					( $context === 'date' ) ||
					( $context === 'date-value' ) ||
					( $context === 'date-value2' ) ||
					( $context === 'sorting' ) ||
					( ( substr( $context, 0, 5 ) === 'field' ) && ( array_pop( ( explode( "_", $context ) ) ) === 'value' ) )
				) {
						$taxonomy = $wordpress_functions->get_taxonomy($post_type, $taxonomy_criteria, $taxonomy_value );
						$terms = $wordpress_functions->get_terms($taxonomy, $term_criteria, $term_value );

						$post_id_based_on_date = $wordpress_functions->get_post_ids_based_on_date( $post_type, $date_criteria, $date_value );
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
						$post_id_array = array_merge( array_intersect( $post_id_based_on_date, $post_id_based_on_term ) );

						$post_id_array = $wordpress_functions->sort_post_id( $post_id_array, $sorting );

						$output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
					}

				if ( $context === 'taxonomy_criteria' ) {
					$taxonomy_obj['criteria'] = $wordpress_functions->get_criteria();
					$taxonomy_obj['value'] = elementary_get_taxonomy_array( $post_type );
					$output['filtersListOL'][0]['taxonomy'] = $taxonomy_obj;

					if ( $taxonomy['criteria'] === 'any' ) {
						$output['filtersListTaxValue'] = elementary_get_taxonomy_array( $post_type );
					}
				}
				elseif ( $context === 'taxonomy_value' ) {
					// $taxonomy = $wordpress_functions->get_taxonomy($post_type, $taxonomy_criteria, $taxonomy_value );
					// $output['the_content'] = $taxonomy;
					// $output['the_content'] = $wordpress_functions->get_data( $atom_type_array, $fields_list, $post_id_array );
				}
				elseif ( $context === 'terms_criteria' ) {
					$term_obj['criteria'] = $wordpress_functions->get_criteria();
					$term_obj['value'] = elementary_get_terms_array( $taxonomy );
					$output['filtersListOL'][0]['terms'] = $term_obj;
					if ( $term['criteria'] === 'any' ) {
						$output['filtersListTermValue'] = elementary_get_terms_array( $taxonomy );
					}
				}
				elseif (
					( $context === 'terms_criteria' ) ||
					( $context === 'terms_value' ) ||
					( $context === 'date' ) ||
					( $context === 'date-value' ) ||
					( $context === 'date-value2' )
				) {
					$output['fieldsListOL'] = $wordpress_functions->get_fields_list( $atom_type_array, $post_id_array );
				}
				elseif ( $context === 'sorting' ) {
				    $output['filtersListOL'][0]['sorting'] = $sorting;
				}
				elseif ( ( substr( $context, 0, 5 ) === 'field' ) && ( array_pop( ( explode( "_", $context ) ) ) === 'type' ) ) {
					$field_key = array_shift( ( explode( "_", $context ) ) );		// May be 'field1', 'field2' etc..
				    if ( array_key_exists( $field_key, $atom_type_array ) ) {
						$field_type = $atom_type_array[$field_key];		// May be 'text' or 'link' or 'image'.
					}
				    else {
				        $wordpress_functions->elementary_log('Invalid Field Key: ' . $field_key );
				    }
				    $field_value_array = $wordpress_functions->get_fields_list( $field_type, $post_id_array );
					if ( array_key_exists( 0, $field_value_array ) ) {
						$output['fieldsListOL'][$field_key] = $field_value_array[0];
					}
				}
			}
			else {

			    // Fallback...

			}

		}



		$output['sandbox']['context'] = $context;
		$output['sandbox']['post_type'] = $post_type;
		$output['sandbox']['post_type_value'] = $post_type_value;
		$output['sandbox']['criteria'] = $criteria;
		$output['sandbox']['comparison'] = $comparison;
		$output['sandbox']['sorting'] = $sorting;
		$output['sandbox']['post_id'] = $post_id;
		$output['sandbox']['post_id_array'] = $post_id_array;
		$output['sandbox']['date_criteria'] = $date_criteria;
		$output['sandbox']['date_val'] = $date_value;
		$output['sandbox']['atom_type_array'] = $atom_type_array;
		$output['sandbox']['fields_list'] = $fields_list;
		$output['sandbox']['taxonomy'] = $taxonomy;
		$output['sandbox']['term'] = $term;
		$output['sandbox']['post_id_based_on_comparison'] = $post_id_based_on_comparison;
		$output['sandbox']['post_id_based_on_date'] = $post_id_based_on_date;
		$output['sandbox']['post_id_based_on_term'] = $post_id_based_on_term;
		wp_send_json( $output );

	}

}
