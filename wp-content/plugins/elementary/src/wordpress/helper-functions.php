<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! class_exists( 'Elementary_WordPress_Functions' ) ) :

    Class Elementary_WordPress_Functions {

        public function flatten_array ( $array ) {
            /*
            	Parameters: Multi dimensional array
            	Description: Flattens the input array to give a one-dimensional array
            	Reference: http://stackoverflow.com/questions/1319903/how-to-flatten-a-multidimensional-array#answer-1320259
            */

            $flat_array = array();
        	$array = (array) $array;	// Typecast to Array
            $iterator = new RecursiveIteratorIterator( new RecursiveArrayIterator( $array ) );
            foreach( $iterator as $value ) {
                $flat_array[] = $value;
            }
            return $flat_array;
        }

        public function print_array( $array ) {
            /*
            	Parameters: $array to be printed
            	Description: Useful for development purpose. Prints the array in a presentable way.
            */
            if ( defined( 'ELEMENTARY_DEBUG_MODE' ) && ELEMENTARY_DEBUG_MODE ) {
                echo "<pre>";
                print_r( $array );
                echo "</pre>";
            }
        }

        public function elementary_log( $log_text ) {
            /*
            	Parameters: Text (String) to be logged.
            	Description: Logs the text in a file if `ELEMENTARY_DEBUG_MODE` is enabled.
            */

            //check for ELEMENTARY_DEBUG_MODE constant status
            if ( defined( 'ELEMENTARY_DEBUG_MODE' ) && ELEMENTARY_DEBUG_MODE ) {
                $backtrace = debug_backtrace();
                $caller = array_shift( $backtrace );
                $log_file = ELEMENTARY_PLUGIN_DIR . "/elementary.log";
                if ( ( is_array( $log_text ) ) || ( is_object( $log_text ) ) ) {
                    error_log( "[" . date("d-M-Y H:i:s T") . "]: ". print_r( $log_text, true ) . ' in ' . $caller['file'] . ' on line ' . $caller['line'] . "\n", 3, $log_file );
                }
                else {
                    error_log( "[" . date("d-M-Y H:i:s T") . "]: ". $log_text . ' in ' . $caller['file'] . ' on line ' . $caller['line'] . "\n", 3, $log_file );
                }
            }
        }

        public function elementary_get_open_tag() {
            return '{';
        }

        public function elementary_get_close_tag() {
           return '}';
       }

       public function elementary_match_url( $text ) {
           /*
               Parameters: $text
               Description: Checks if the $text passed contains an URL
           */

           $reg_exp = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
           if ( preg_match( $reg_exp, $text ) ) {
               return true;
           }
           else {
               return false;
           }
       }

       public function elementary_match_store_keys( $text ) {
          /*
              Parameters: $text
              Description: Checks if the $text passed ends with 'Store'
          */

          $reg_exp = "/Store$/";
          if ( preg_match( $reg_exp, $text ) ) {
              return true;
          }
          else {
              return false;
          }
      }

        public function add_suffix( $array, $suffix ) {
             /*
                 Description: Adds the suffix to each value in the array
             */
             foreach ( $array as &$value ) {
                 $value = $value . $suffix;
             }
             return $array;
        }

        public function get_unique_value( $val, $arr ) {
            /*
                Description: Generates unique values and replaces recurring values in array.
                Reference: http://stackoverflow.com/a/17155969/4003138
            */
            if ( in_array( $val, $arr ) ) {
                $d = 2; // initial suffix
                preg_match( "~([\d])$~", $val, $matches ); // check if value has suffix
                $d = $matches ? (int)$matches[1]+1 : $d;  // increment suffix if exists

                preg_match( "~(.*)[\d]$~", $val, $matches );

                $newval = ( in_array( $val, $arr ) ) ? $this->get_unique_value( $matches ? $matches[1].$d : $val.$d, $arr ) : $val;
                return $newval;
            }
            else {
                return $val;
            }
        }

        public function replace_recurring_values( $arr ) {
           /*
               Description: Generates unique values and replaces recurring values in array.
               Uses get_unique_value().
               Reference: http://stackoverflow.com/a/17155969/4003138
           */
           $_arr = array();
           foreach ( $arr as $k => $v ) {
               $arr[$k] = $this->get_unique_value( $v, $_arr );
               $_arr[$k] = $arr[$k];
           }
           unset( $_arr );

           return $arr;
       }

        public function functionally_empty( $o ) {
            /*
                Parameters: Object to be checked
                Description: Test If Items in Array or Object are Empty
                Reference: http://technify.me/code-snippets/php-test-if-items-in-array-or-object-empty/
            */
            if ( empty( $o ) ) {
                return true;
            }
            elseif ( is_numeric( $o ) ) {
                return false;
            }
            elseif ( is_string( $o ) ) {
                return !strlen(trim($o));
            }
            elseif ( is_object( $o ) ) {
                return $this->functionally_empty( (array) $o );
            }

            // It's an array!
            foreach( $o as $element ) {
                if ( $this->functionally_empty( $element ) ) continue; // so far so good.
                else return false;
            }

            // all good.
            return true;
        }

        public function update_meta_key( $old_key=null, $new_key=null ) {
           /*
               Description: Rename meta keys
               Usage: update_meta_key( 'old_key', 'new_key');
               Reference: https://gist.github.com/zanematthew/3199265
           */
           global $wpdb;
           $query = "UPDATE ".$wpdb->prefix."postmeta SET meta_key = '".$new_key."' WHERE meta_key = '".$old_key."'";
           $results = $wpdb->get_results( $query, ARRAY_A );
           return $results;
       }

        public function elementary_list_pluck( $list, $field ) {
           /*
               Description: Pluck a certain field out of each object in a list. Based on the native WP function wp_list_pluck()
               Reference: https://core.trac.wordpress.org/browser/tags/4.5/src/wp-includes/functions.php#L3441
           */
           $newlist = array();
           foreach ( $list as $key => $value ) {
               if ( ( $value ) && ( is_array( $value ) ) ) {
                   if ( array_key_exists( $field, $value ) ) {
                       $newlist[] = $value[ $field ];
                   }
               }
           }
           return $newlist;
       }
        public function include_exclude_logic( $items, $criteria, $value ) {
            /*
                Parameters:
                $criteria: Criteria based on which the items are to be selected
                $value: List of items
                Description: Outputs the array of items based on user selection criteria.
            */

            $items = (array) $items;	// Typecast to Array
            $value = (array) $value;	// Typecast to Array

        	if ( $criteria !== 'any' ) {
                if ( $criteria === 'exclude' ) {
                    $items = array_diff( $items, $value );
                }
                elseif ( $criteria === 'include' ) {
                    $items = $value;
                }
                else {
                    $this->elementary_log( 'Invalid criteria' );
                }
            }
            return $items;
        }

        public function get_filter_criteria() {
            /*
                Parameters:
                Description: Returns the default filter criteria.
            */

            return array('field', 'taxonomy');
        }

        public function get_criteria() {
            /*
                Parameters:
                Description: Returns the default criteria.
            */

            return array('any', 'include', 'exclude');
        }

        public function get_fields() {
            /*
                Parameters:
                Description: Returns the default criteria.
            */

            return array('post-id');
        }

        public function get_comparison() {
            /*
                Parameters:
                Description: Returns the default criteria.
            */

            return array('greater-than', 'lesser-than', 'equal-to');
        }

        public function get_sorting_options() {
            /*
                Parameters:
                Description: Returns the default sorting options.
            */

            return array('ascending', 'descending');
        }

        public function get_post_types( $criteria, $value ) {
            /*
                Parameters:
                $criteria: Criteria based on which the post types are to be selected
                $value: List of posts
                Description: Outputs the array of post types based on user selection criteria.
            */
            $post_types = array();
            // if ( $value ) {
        		$post_types = elementary_get_all_post_types();
            	$post_types = $this->include_exclude_logic( $post_types, $criteria, $value );
                $post_types = array_values( $post_types );
            // }
            return $post_types;
        }

        public function get_taxonomy( $post_type, $criteria, $value ) {
            /*
                Parameters:
                $post_types: The post types whose taxonomies are to be returned.
                $criteria: Criteria based on which the taxonomies are to be selected
                $value: List of taxonomies
                Description: Outputs the array of taxonomies based on user selection criteria.
            */
            $taxonomy = array();
            $taxonomy = get_object_taxonomies( $post_type );
        	$taxonomy = $this->include_exclude_logic( $taxonomy, $criteria, $value );
            $taxonomy = array_values( $taxonomy );
            return $taxonomy;
        }

        public function get_terms( $taxonomy, $criteria, $value ) {
            /*
                Parameters:
                $taxonomy: The $taxonomies whose terms are to be returned.
                $criteria: Criteria based on which the terms are to be selected
                $value: List of taxonomies
                Description: Outputs the array of terms based on user selection criteria.
            */
            $terms = array();
            $terms = elementary_get_terms_array( $taxonomy );
        	$terms = $this->include_exclude_logic( $terms, $criteria, $value );
            $terms = array_values( $terms );
            return $terms;
        }

        public function get_post_type_from_taxonomy( $taxonomy ) {
            /*
                Parameters:
                $taxonomy: The $taxonomies whose terms are to be returned.
                Description: Returns the post-type to which this taxonomy belongs.
            */

            $post_type = array();
            if ( $taxonomy ) {
                $tax_object = get_taxonomy($taxonomy);
                $post_type = $tax_object->object_type;
            }
            return $post_type;

        }

        public function get_post_ids_from_term( $term_criteria, $term_value ) {
            /*
                Parameters: $term
                Description: Outputs an array of post ids corresponding to this term.
            */

        	$posts_array = $tax_query = array();
            $term_array = (array) $term_value;	// Typecast to Array
            foreach ( $term_array as $term ) {
                $taxonomy = elementary_get_taxonomy_from_term( $term );
                $post_type = $this->get_post_type_from_taxonomy( $taxonomy );

                if ( $term_criteria === 'include' ) {
                    $tax_query = array(
                        array(
                            'taxonomy'	=> $taxonomy,
                            'field'		=> 'slug',
                            'terms'		=> $term,
                            'operator'	=> 'IN',
                        )
                    );
                }
                elseif ( $term_criteria === 'exclude' ) {
                    $tax_query = array(
                        array(
                            'taxonomy'	=> $taxonomy,
                            'field'		=> 'slug',
                            'terms'		=> $term,
                            'operator'	=> 'NOT IN',
                        )
                    );
                }

            	$args = array(
            		'posts_per_page'      => -1,
            		'post_type'           => $post_type,
            		'orderby'             => 'ID',
            		'tax_query'           => $tax_query,
            	);
            	$posts = get_posts( $args );
            	if ( $posts ) {
            		foreach ( $posts as $post ) {
            			$posts_array[] = $post->ID;
            		}
            	}
            	wp_reset_postdata();
            }
            if ( $posts_array ) {
                $posts_array = array_merge( array_unique( $posts_array ) );
                rsort( $posts_array );
            }
        	return $posts_array;
        }


        public function get_post_ids_from_post_type( $post_types ) {
            /*
                Parameters:
                $post_types:
                Description: Returns an array of Post ids matching the post_types.
            */
            $post_ids  = array();
        	$post_types = (array) $post_types;	// Typecast to Array
            if ( !$this->functionally_empty( $post_types ) ) {
                foreach ( $post_types as $post_type ) {
            		$posts = get_posts( array(
                        'posts_per_page'	=> -1,
            		    'post_type'		=> $post_type
            		));
            		if ( $posts ) {
            			foreach ( $posts as $post ) {
            				$post_ids[] = $post->ID;
            			}
            		}
            	}
                if ( $post_ids ) {
                    $post_ids = array_merge( array_unique( $post_ids ) );
                    rsort( $post_ids );
                }
            }
            return $post_ids;
        }

        public function get_post_ids_based_on_comparison( $post_id_array, $comparison, $post_id ) {
            /*
                Parameters:
                $post_id_array: The list of post ids that will be filtered.
                $comparison:
                $post_id:
                Description: Outputs the array of post ids based on user selection comparison.
            */

        	$output = array();
            $post_id_array = (array) $post_id_array;	// Typecast to Array
        	if ( $comparison === 'equal-to' ) {
        		$output[] = $post_id;
        	}
        	else {
    			if ( $comparison === 'lesser-than' ) {
    				foreach ( $post_id_array as $id ) {
    					if ( $id < $post_id ) {
    						$output[] = $id;
    					}
    				}
    			}
    			elseif ( $comparison === 'greater-than' ) {
    				foreach ( $post_id_array as $id ) {
    					if ( $id > $post_id ) {
    						$output[] = $id;
    					}
    				}
    			}
    			else {
    				$this->elementary_log('Invalid comparison: ' . $comparison );
    			}
        	}
        	return $output;
        }

        public function get_post_ids_based_on_date( $post_type, $date_criteria, $date_value_array ) {
            /*
                Parameters:
                $post_type: The post types from which posts are to be fetched.
                $date_criteria:
                $date_value_array: Date parameters.
                Description: Outputs the array of post ids based on date parameters.
            */

            $post_ids  = array();
            $date_value1 = $date_value2 = '';
        	$post_type = (array) $post_type;	// Typecast to Array
        	$date_value_array = (array) $date_value_array;	// Typecast to Array

            if ( array_key_exists( 0, $date_value_array ) ) {
                $date_value1 = $date_value_array[0];
            }
            if ( array_key_exists( 1, $date_value_array ) ) {
                $date_value2 = $date_value_array[1];
            }


            if ( $date_criteria === 'any' ) {
                $date_query = '';
            }
            elseif ( $date_criteria === 'before/after' ) {
                $date_query = array(
                    array(
                        'before'    => $date_value1,
                        'after'     => $date_value2,
                        'inclusive' => true,
                    ),
                );
            }
            elseif ( $date_criteria === 'on' ) {
                $date = date_parse_from_format("Y-m-d", $date_value1);
                $date_query = array(
                    array(
            			'year'  => $date['year'],
            			'month' => $date['month'],
            			'day'   => $date['day'],
            		),
                );
            }
            elseif ( $date_criteria === 'last' ) {
                $date_query = array(
                    array(
                        'after'     => $date_value1 . ' days ago',
                        'inclusive' => true,
                    ),
                );
            }


    		$posts = get_posts( array(
                'posts_per_page'	=> -1,
    		    'post_type'		=> $post_type,
                'date_query' => $date_query,
    		));
    		if ( $posts ) {
    			foreach ( $posts as $post ) {
    				$post_ids[] = $post->ID;
    			}
    		}
            else {
                $this->elementary_log('No posts matching the date_query' );
            }
            if ( $post_ids ) {
                $post_ids = array_merge( array_unique( $post_ids ) );
                rsort( $post_ids );
            }
            return $post_ids;
        }

        public function sort_post_id( $post_id_array, $sorting ) {
            /*
                Parameters:
                $post_id_array: The list of post ids that will be sorted.
                $sorting:
                Description: Outputs the array of post ids based the sorting parameter.
            */

            $post_id_array = (array) $post_id_array;	// Typecast to Array
            if ( !$sorting ) {
                $sorting = 'descending';
            }
            if ( $sorting === 'ascending' ) {
    			sort( $post_id_array );
    		}
    		elseif ( $sorting === 'descending' ) {
    			rsort( $post_id_array );
    		}
            else {
                $this->elementary_log('Invalid sorting option: ' . $sorting );
            }
            return $post_id_array;

        }

        public function get_fields_list( $atom_type_array, $post_id_array ) {
            /*
                Parameters:
                $atom_type_array: Array of content types.
                $post_id_array: Array of Post_ids to retrieve the fields from
                Description: Returns the fields corrresponding to the post_ids.
            */
            $output = $output_temp = array();
            $atom_type_array = (array) $atom_type_array;	// Typecast to Array
            $post_id_array = (array) $post_id_array;	// Typecast to Array

            if ( $post_id_array ) {

                foreach ( $atom_type_array as $key => $value ) {
                    if ( $atom_type_array[$key] === 'image' ) {
                        $images_array = elementary_get_images_title( $post_id_array );
                        if ( $images_array ) {
                            $output[$key] = array();
                            array_push( $output[$key], $images_array );
                            $output[$key] = $this->flatten_array($output[$key]);
                        }
                    }
                    elseif ( $atom_type_array[$key] === 'text' ) {
                        foreach ( $post_id_array as $post_id ) {
                            $output_temp = array();
                            $output_temp = array_merge( $output_temp, elementary_get_text_fields( $post_id ) );
                            $output[$key] = $output_temp;
                        }
                    }
                    elseif ( $atom_type_array[$key] === 'link' ) {
                        foreach ( $post_id_array as $post_id ) {
                            $output_temp = array();
                            $output_temp = array_merge( $output_temp, elementary_get_url_fields( $post_id ) );
                            $output[$key] = $output_temp;
                        }
                    }
                    elseif ( $atom_type_array[$key] === 'button' ) {
                        foreach ( $post_id_array as $post_id ) {
                            $output_temp = array();
                            $output_temp = array_merge( $output_temp, elementary_get_button_fields( $post_id ) );
                            $output[$key] = $output_temp;
                        }
                    }
                    elseif ( $atom_type_array[$key] === 'rating' ) {
                        foreach ( $post_id_array as $post_id ) {
                            $output_temp = array();
                            $output_temp = array_merge( $output_temp, elementary_get_rating_fields( $post_id ) );
                            $output[$key] = $output_temp;
                        }
                    }

                    elseif ( $atom_type_array[$key] === 'color' ) {
                        foreach ( $post_id_array as $post_id ) {
                            $output_temp = array();
                            $output_temp = array_merge( $output_temp, elementary_get_color_fields( $post_id ) );
                            $output[$key] = $output_temp;
                        }
                    }
                    else {
                        $this->elementary_log('Invalid atom type: ' . $atom_type_array[$key] . '. Unable to get_fields_list()');
                    }
                }
            }
            else {
                $this->elementary_log('$post_id_array is empty. Unable to get_fields_list()');
            }

            return $output;
        }

        public function get_data( $atom_type_array, $field_value_array, $post_id_array ) {
            /*
                Parameters:
                $atom_type_array: Array of content types.
                $field_value_array: Array of the data fields.
                $post_id_array: Array of Post_ids to retrieve the data from
                Description: Returns the data corrresponding to the post_ids. Also prepends the post_id by an underscore '_' to prevent the browser from resorting the numeric keys.
                Reference: http://javascript.info/tutorial/objects#highlighter_689126
            */
            $output = array();
            $atom_type_array = (array) $atom_type_array;	// Typecast to Array
            $field_value_array = (array) $field_value_array;	// Typecast to Array
            $post_id_array = (array) $post_id_array;	// Typecast to Array

            if ( $post_id_array ) {
                $atom_type_count = count( $atom_type_array );
                $field_value_count = count( $field_value_array );

                if ( $atom_type_count === $field_value_count ) {

                    foreach ( $atom_type_array as $key => $value ) {
                        $field_value = $field_value_array[$key];
                        if ( $atom_type_array[$key] === 'image' ) {
                    		$text = sanitize_file_name( $field_value );
                    		$attachment_number = substr( $text, -1 );
                            if ( $attachment_number ) {
                                $validate = new Elementary_Validate();
                    		    $attachment_number = $validate->validate( 'attachment-number', $attachment_number );
                            }
                    		$option = preg_replace( '/\d/', '', $text );	// Remove number
                    		$option = rtrim( $option, "-" );	// Trim hyphen

                            foreach ( $post_id_array as $post_id ) {
                                $output[ '_' . $post_id][$key] = elementary_get_image_url( $option, $post_id, $attachment_number );
                            }
                    	}
                    	elseif ( ( $atom_type_array[$key] == 'text' ) || ( $atom_type_array[$key] == 'link' ) ) {
                            foreach ( $post_id_array as $post_id ) {
                                $output[ '_' . $post_id][$key] = elementary_get_text_field_data( $post_id, $field_value );
                            }
                    	}
                        elseif ( $atom_type_array[$key] === 'button' ) {
                            foreach ( $post_id_array as $post_id ) {
                                $output[ '_' . $post_id][$key] = elementary_button_field_data( $post_id, $field_value );
                            }
                    	}
                        elseif ( $atom_type_array[$key] === 'rating' ) {
                            foreach ( $post_id_array as $post_id ) {
                                $output[ '_' . $post_id][$key] = elementary_button_field_data( $post_id, $field_value );
                            }
                    	}
                        elseif ( $atom_type_array[$key] === 'color' ) {
                            foreach ( $post_id_array as $post_id ) {
                                $output[ '_' . $post_id][$key] = elementary_button_field_data( $post_id, $field_value );
                            }
                    	}
                    	else {
                            $this->elementary_log('Invalid atom type: ' . $atom_type_array[$key] . '. Unable to get_data()');
                    	}
                    }

                }
                else {
                    $this->elementary_log('Number of items in atom_type_array(' . count( $atom_type_array ) . ') and field_value_array(' . count( $field_value_array ) . ') do not match. Unable to get_data()');
                }
            }
            else {
                $this->elementary_log('$post_id_array is empty. Unable to get_data()');
            }

        	return $output;
        }

    }

endif;
