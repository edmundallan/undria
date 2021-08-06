<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wordpress_functions;


function elementary_get_all_post_types() {
	/*
		Parameters: None
		Description: Outputs an array of post types on page-load.
	*/
	$post_types = get_post_types( array( 'public' => true ) );
	$post_type_array = array();
	if ( $post_types ) {
		foreach ( $post_types as $key => $value ) {
			$post_type_array[] = $value;
		}
		$post_type_array = apply_filters( 'elementary_posts_array', $post_type_array );
	}
	return $post_type_array;
}


function elementary_get_taxonomy_array( $post_type ) {
	/*
		Parameters: post_type. Can be a string or array of post_types
		Description: Outputs an array of taxonomies belonging to that post_type.
	*/

	$taxonomy_array = array();
	if ( $post_type ) {
		$taxonomy_array = get_object_taxonomies( $post_type );
		$taxonomy_array = apply_filters( 'elementary_taxonomy_array', $taxonomy_array );
	}
	return $taxonomy_array;
}


function elementary_get_taxonomy_from_id( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the taxonomies corresponding to this post_id.
	*/

	$post_taxonomy_array = array();
	$post_type = get_post_type( $post_id );
    $taxonomy_array = elementary_get_taxonomy_array( $post_type );
	return $taxonomy_array;
}


function elementary_get_terms_array( $taxonomy ) {
	/*
		Parameters: taxonomy. Can be a string or array of taxonomies
		Description: Outputs an array of terms belonging to that taxonomy.
	*/

	$terms = '';
	$terms_array = array();

	$terms = get_terms( $taxonomy );
	if ( $terms && ( !is_wp_error( $terms ) ) ) {
		foreach ( $terms as $term ) {
			$terms_array[] = $term -> name;
		}
	}
	return $terms_array;
}


function elementary_get_taxonomy_from_term ( $term_name ) {
	/*
		Parameters: term_name
		Description: Returns the name of the taxonomy to which this term belongs to.
	*/
	if ( !( is_array( $term_name ) ) ) {
		$taxonomies = get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$term = get_term_by( 'name', $term_name, $taxonomy );
			if ( $term ) {
				return $term->taxonomy;
			}
		}
	}
	else {
		$wordpress_functions->elementary_log( 'Invalid Term.' );
	}
	return null;
}


function elementary_get_attached_images_title_helper( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the title of attached images (excluding the featured image) corresponding to this post_id, if they exist. [i.e Attachment 1 , Attachment 2 etc]
	*/
	$attachments = '';
	$attached_images = array();
	if ( $post_id && is_numeric( $post_id ) ) {
		$args = array(
			'post_type'		=> 'attachment',
			'post_mime_type' => 'image',
			'posts_per_page'	=> -1,
			'post_status'	=> 'inherit',
			'post_parent'	=> $post_id,
			'exclude'		=> get_post_thumbnail_id( $post_id ),
		);

		$attachments = get_posts( $args );

	}

	if ( $attachments ) {
		$count = 1;
		foreach ( $attachments as $attachment ) {
			$attached_images[] = 'Attachment ' . $count;
			$count++;
		}
	}
	return $attached_images;
}


function elementary_get_attached_images_title( $post_ids_array ) {
	/*
		Parameters: $post_ids_array
		Description: Outputs the title of attached images (excluding the featured image) corresponding to these post_ids. [i.e Attachment 1 , Attachment 2 etc]
	*/
	$output = array();
	$post_ids_array = (array) $post_ids_array;	// Typecast to Array
	foreach ( $post_ids_array as $post_id ) {
		$attached_images = elementary_get_attached_images_title_helper( $post_id );
		$output[] = $attached_images;
	}
	if ( count( $output ) > 1 ) {
		$intersect = call_user_func_array( 'array_intersect', $output );
	}
	else {
		$intersect = $output[0];
	}
	return $intersect;
}


function elementary_get_featured_image_title( $post_ids_array ) {
	/*
		Parameters: $post_id
		Description: Outputs the text 'Featured Image'
	*/

	return array( 'Featured Image' );
}


function elementary_get_images_title( $post_ids_array ) {
    global $wordpress_functions;
	/*
		Parameters: $post_id
		Description: Outputs the title of all the images corresponding to these post_ids. [i.e Featured Image, Attachment 1 , Attachment 2 etc]
	*/

	$images_array = array();
	$featured_image_array = elementary_get_featured_image_title( $post_ids_array );
	$attached_images = elementary_get_attached_images_title( $post_ids_array );
    $post_type = get_post_type( $post_ids_array[0] );
    $elementary_meta = new Elementary_Meta();

    if ( ( $elementary_meta->is_woocommerce_installed() ) && $post_type == 'product' ) {
        $term_image = array('Product Category Image');
        $images_array = array_merge( $featured_image_array, $attached_images, $term_image );
    }else{
        $images_array = array_merge( $featured_image_array, $attached_images );
    }

	return $wordpress_functions->flatten_array( $images_array );
}


function elementary_get_featured_image_url( $post_id ) {

	/*
		Parameters: $post_id
		Description: Outputs the URL of featured image corresponding to this post_id, if it exists
	*/

	if ( $post_id && is_numeric( $post_id ) ) {
		if ( has_post_thumbnail( $post_id ) ) {
			$featured_img_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
			$featured_img_url = $featured_img_attributes[0];
			return esc_url( $featured_img_url );
		}
	}
}


function elementary_get_term_image_url( $post_id ) {

	/*
		Parameters: $post_id
		Description: Outputs the URL of featured image corresponding to this post_id, if it exists
	*/

	if ( $post_id && is_numeric( $post_id ) ) {
            $post = get_post( $post_id );

            if ( $post ) {
                // $taxonomy_array = elementary_get_taxonomy_from_id( $post_id );
                $terms = get_the_terms($post_id, 'product_cat'); // for get term id
            }
            foreach ($terms as $term) {
                $term_id = $term->term_id;
                break;
            }
            // return $term_id;
             $thumbnail_id = get_woocommerce_term_meta( $term_id, 'thumbnail_id', true );


		if ( $thumbnail_id ) {
			 $image = wp_get_attachment_url( $thumbnail_id );
			 return esc_url( $image );
		}
	}
}


function elementary_get_attachment_image_url( $post_id, $attachment_number ) {
	/*
		Parameters: $post_id
		Description: Outputs the URL of the attached image corresponding to this post_id with $attachment_number as index, if it exists
	*/
	$attachments = array();
	$attachment_url = '';
	if ( ( $post_id && is_numeric( $post_id ) ) && ( $attachment_number && is_numeric( $attachment_number ) ) ) {
		$args = array(
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'posts_per_page' => -1,
			'post_status' => 'inherit',
			'post_parent' => $post_id,
			'exclude'     => get_post_thumbnail_id( $post_id ),
		);

		$attachments = get_posts( $args );
	}

	if ( $attachments ) {
		$offset = $attachment_number - 1;
		if ( array_key_exists( $offset, $attachments ) ) {
			$attachment_url = wp_get_attachment_url( $attachments[$offset] -> ID );
		}
	}
	return esc_url( $attachment_url );
}


function elementary_get_image_url( $option, $post_id, $attachment_number ) {

	/*
		Parameters: $option, $post_id, $attachment_number
		Description: Outputs the image URL
	*/

	$image_url = '';
	if ( $post_id && is_numeric( $post_id ) ) {
		if ( $option === 'Featured-Image' ) {
			if ( has_post_thumbnail( $post_id ) ) {
				$image_url = elementary_get_featured_image_url( $post_id );
			}
			else {
				$image_url = apply_filters( 'elementary_placeholder_img_url', ELEMENTARY_PLUGIN_IMG_DIR . '/elementary-placeholder.jpg' );
			}
		}
		elseif  ( $option == 'Attachment' ) {
			$image_url = elementary_get_attachment_image_url( $post_id, $attachment_number );
		}
        elseif (  $option == 'Product-Category-Image' ){
            $image_url = elementary_get_term_image_url( $post_id );
            // $image_url = "Term Image Value";
        }
	}
	return esc_url( $image_url );
}


function elementary_get_post_title_field() {
	/*
		Parameters: $post_id
		Description: Outputs the text 'Title'
	*/

	return array( 'Title' );
}


function elementary_get_post_excerpt_field(){
	/*
		Description: Outputs the text 'Excerpt'
	*/

	return array( 'Excerpt' );
}


function elementary_get_post_content_field() {
	/*
		Description: Outputs the text 'Post Content'
	*/

	return array( 'Post Content' );
}


function elementary_get_post_meta_field( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the meta fields corresponding to this post_id. Hides the invisible meta fields. (i.e the ones that begin with an underscore)
		Exclude the "_*" custom fields.
		Reference: https://crappycode.wordpress.com/2013/02/12/grab-all-the-post-meta/
	*/

	$meta_data = get_post_meta( $post_id );
	$post_type = get_post_type( $post_id );
	$post_meta_array = array();
	$elementary_meta = new Elementary_Meta();

	if ( $meta_data ) {
		foreach ( $meta_data as $key => $val ) {
			if( '_' != $key[0] ) {
				$post_meta_array[] = $key;
			}
		}
	}
	if ( ( $elementary_meta->is_woocommerce_installed() ) && $post_type == 'product' ) {
		$post_meta_array = array_merge( $post_meta_array, array( '_regular_price', '_price' ) );
	}
	$post_meta_array = apply_filters( 'elementary_meta_fields', $post_meta_array );
	return $post_meta_array;
}


function elementary_get_text_fields( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs all the text fields corresponding to this post_id
	*/
    global $wordpress_functions;
    // global $wordpress_functions;
	$text_field_array = array();
	$post_title_array = elementary_get_post_title_field();
	$post_excerpt_array = elementary_get_post_excerpt_field();
	$post_content_array = elementary_get_post_content_field();
	$post_meta_array = elementary_get_post_meta_field( $post_id );
	$post_taxonomy_array = elementary_get_taxonomy_from_id( $post_id );

	$text_field_array = array_merge( $post_title_array, $post_excerpt_array, $post_content_array, $post_meta_array, $post_taxonomy_array );

	$text_field_array = $wordpress_functions->flatten_array( $text_field_array );
	$text_field_array = array_diff( $text_field_array, array('Any') );
	$text_field_array = array_values( $text_field_array );
	return $text_field_array;

}


function elementary_get_post_title( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the excerpt corresponding to this post_id
	*/

	$post_title = get_the_title( $post_id );
	return $post_title;
}


function elementary_get_post_excerpt( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the excerpt corresponding to this post_id
	*/

	$post_excerpt = '';
	if ( $post_id && is_numeric( $post_id ) ) {
		$post = get_post( $post_id );
		if ( $post ) {
			$post_excerpt = $post->post_excerpt;
		}
	}
	return $post_excerpt;
}


function elementary_get_post_content( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the excerpt corresponding to this post_id
	*/

	$post_content = '';
	if ( $post_id && is_numeric( $post_id ) ) {
		$sanitize = new Elementary_Sanitize();
		$post = get_post( $post_id );
		$post_content = $sanitize->sanitize_text( $post->post_content );
	}
	return $post_content;
}


function elementary_get_post_meta_data( $post_id, $metakey ) {
	/*
		Parameters: $post_id
		Description: Outputs the meta field of the $post_id corresponding to this $metakey.
	*/

	$meta_data = '';
	if ( $post_id && is_numeric( $post_id ) && $metakey && is_string( $metakey ) ) {
		$meta_data =  get_post_meta( $post_id, $metakey, true );
	}
	return $meta_data;
}


function elementary_get_post_url_field() {
	/*
		Description: Outputs the text 'Post URL'
	*/
	return array( 'Post URL' );
}


function elementary_get_meta_url_fields( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the meta fields corresponding to this post_id that have an URL in its meta value
	*/
    global $wordpress_functions;

	$meta_data = get_post_meta( $post_id, '', false );
	$url_fields = array();

	if ( $meta_data ) {
		foreach ( $meta_data as $metakey => $val ) {
			$meta_content =  get_post_meta( $post_id, $metakey, true );
			if ( ( $meta_content ) && ( !is_array( $meta_content ) ) ) {
				if ( $wordpress_functions->elementary_match_url( $meta_content ) ) {
					$url_fields[] = $metakey;
				}
			}
		}
	}
	return $url_fields;
}


function elementary_get_matching_keys( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the meta keys corresponding to this post_id that end in 'Store'
	*/

	$all_meta_keys = get_post_custom_keys( $post_id );
	$matching_meta_keys = array();

	if ( $all_meta_keys ) {
        global $wordpress_functions;
		foreach ( $all_meta_keys as $metakey ) {
			if ( $wordpress_functions->elementary_match_store_keys( $metakey ) ) {
				$metakey = ltrim( $metakey, '_');
				$matching_meta_keys[] = $metakey;
			}
		}
	}
	return $matching_meta_keys;
}


function elementary_get_url_fields( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs all the URL fields corresponding to this post_id
	*/

	$url_array = array();
	$post_url = elementary_get_post_url_field();
	$meta_url_array = elementary_get_meta_url_fields( $post_id );
	$url_array = array_merge( $post_url, $meta_url_array );
	return $url_array;

}


function elementary_get_post_url( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs the URL of the post corresponding to this post_id
	*/
	$post_url = '';
	if ( $post_id && is_numeric( $post_id ) ) {
		$post_url =  get_permalink( $post_id );
	}
	return esc_url( $post_url );
}


function elementary_get_button_fields( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs all the fields related to button corresponding to this post_id
	*/

	$url_array = array();
	$elementary_meta = new Elementary_Meta();
	$post_type = get_post_type( $post_id );
	$post_url = elementary_get_post_url_field();
	$post_taxonomy_array = elementary_get_taxonomy_from_id( $post_id );
	$url_array = array_merge( $post_url, $post_taxonomy_array );
	if ( ( $elementary_meta->is_woocommerce_installed() ) && $post_type == 'product' ) {
		$url_array = array_merge( $url_array, array( 'add-to-cart', 'number-of-rating', 'product-rating', 'full-post-popup' ) );
	}
	return $url_array;
}


function elementary_get_rating_fields( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs all the fields related to button corresponding to this post_id
	*/

	$url_array = array();
	$elementary_meta = new Elementary_Meta();
	$post_type = get_post_type( $post_id );
	$post_url = elementary_get_post_url_field();
	// $post_taxonomy_array = elementary_get_taxonomy_from_id( $post_id );
	// $url_array = array_merge( $post_url, $post_taxonomy_array );
	if ( ( $elementary_meta->is_woocommerce_installed() ) && $post_type == 'product' ) {
		$url_array = array('product-rating');
	}
	return $url_array;
}





function elementary_get_color_fields( $post_id ) {
	/*
		Parameters: $post_id
		Description: Outputs all the fields related to button corresponding to this post_id
	*/

    $url_array = array();
	$elementary_meta = new Elementary_Meta();
    $post_type = get_post_type( $post_id );
	$post_url = elementary_get_post_url_field();
	if ( ( $elementary_meta->is_woocommerce_installed() ) && $post_type == 'product' ) {
		$url_array = array( 'pa_color');
	}
	return $url_array;

}


function elementary_wc_get_rating_count( $product_id, $value = null  ) {
	/*
		Parameters: $product_id
		Description: Returns the total number of ratings for a particular product. Tweaked the core Woocommerce code to suit the need.
		Reference: https://plugins.svn.wordpress.org/woocommerce/tags/2.4.9/includes/abstracts/abstract-wc-product.php
	*/
	$transient_name = 'wc_rating_count_' . $product_id . WC_Cache_Helper::get_transient_version( 'product' );

	if ( ! is_array( $counts = get_transient( $transient_name ) ) ) {
		global $wpdb;
		$counts     = array();
		$raw_counts = $wpdb->get_results( $wpdb->prepare("
			SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = %d
			AND comment_approved = '1'
			AND meta_value > 0
			GROUP BY meta_value
		", $product_id ) );

		foreach ( $raw_counts as $count ) {
			$counts[ $count->meta_value ] = $count->meta_value_count;
		}

		set_transient( $transient_name, $counts, DAY_IN_SECONDS * 30 );
	}

	if ( is_null( $value ) ) {
		return array_sum( $counts );
	} else {
		return isset( $counts[ $value ] ) ? $counts[ $value ] : 0;
	}
}


function elementary_wc_get_average_rating( $product_id ) {
	/*
		Parameters: $product_id
		Description: Returns the average ratings for a particular product. Tweaked the core Woocommerce code to suit the need.
		Reference: https://plugins.svn.wordpress.org/woocommerce/tags/2.4.9/includes/abstracts/abstract-wc-product.php
	*/
	$transient_name = 'wc_average_rating_' . $product_id . WC_Cache_Helper::get_transient_version( 'product' );

	if ( false === ( $average_rating = get_transient( $transient_name ) ) ) {

		global $wpdb;

		$average_rating = '';
		$count          = elementary_wc_get_rating_count($product_id);

		if ( $count > 0 ) {

			$ratings = $wpdb->get_var( $wpdb->prepare("
				SELECT SUM(meta_value) FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0
			", $product_id ) );

			$average_rating = number_format( $ratings / $count, 2 );
		}

		set_transient( $transient_name, $average_rating, DAY_IN_SECONDS * 30 );
	}

	return $average_rating;
}


function elementary_get_text_field_data ( $post_id, $option ) {
	$output = '';

	if ( $post_id && is_numeric( $post_id ) ) {

		$taxonomy_array = elementary_get_taxonomy_from_id( $post_id );
		if ( in_array( $option, $taxonomy_array ) ) {
	        $output = get_the_term_list( $post_id, $option, '', ', ', '' );
	        $output = strip_tags( $output );
	    }
		else {
			if ( $option == 'Title' ) {
				$output = elementary_get_post_title( $post_id );
			}
			elseif ( $option == 'Excerpt' ) {
				$output = elementary_get_post_excerpt( $post_id );
			}
			elseif  ( $option == 'Post Content' ) {
				$output = elementary_get_post_content( $post_id );
			}
			elseif ( $option == 'Post URL' ) {
				$output =  elementary_get_post_url( $post_id );
			}
            elseif ( $option == '_price' || $option == '_regular_price' ){
				$output = elementary_price_data( $post_id, $option );
			}
			else {
				$output = elementary_get_post_meta_data( $post_id, $option );
			}
		}
	}

	return $output;
}


function elementary_button_field_data ( $post_id, $option ) {
	$output = '';
	$elementary_meta = new Elementary_Meta();

	if ( $post_id && is_numeric( $post_id ) ) {

		$taxonomy_array = elementary_get_taxonomy_from_id( $post_id );
		if ( in_array( $option, $taxonomy_array ) ) {
	        $terms = get_the_terms( $post_id, $option );
			$count = 0;
			foreach ( $terms as $term ) {
				$output[$count]['term_url'] = get_term_link( $term );
				$output[$count]['term_name'] = $term->name;
                $output[$count]['term_description'] = $term->description;
				$count++;
			}
	    }
		elseif ( $option == 'Post URL' ) {
			$output =  elementary_get_post_url( $post_id );
		}
		elseif ( $option == 'add-to-cart' ) {
			if ( $elementary_meta->is_woocommerce_installed() ) {
				$output = esc_url_raw( add_query_arg( 'add-to-cart', $post_id, get_permalink() ) );
			}
		}
		elseif ( $option == 'number-of-rating' ) {
			if ( $elementary_meta->is_woocommerce_installed() ) {
				$output = elementary_wc_get_rating_count( $post_id );
			}
		}
		elseif ( $option == 'product-rating' ) {
			if ( $elementary_meta->is_woocommerce_installed() ) {
				$output['value'] = elementary_wc_get_average_rating( $post_id );
                $output['count'] = elementary_wc_get_rating_count( $post_id );
			}
		}
		elseif ( $option == 'buy-now' ) {
			if ( $elementary_meta->is_woocommerce_installed() ) {
				$checkout_url = wc_get_page_permalink( 'checkout' );
				$output = esc_url( add_query_arg( 'add-to-cart', $post_id, $checkout_url ) );
			}
		}
        elseif ( $option == 'full-post-popup' ) {
			$output['title'] = elementary_get_post_title($post_id);
            $output['content'] = elementary_get_post_content($post_id);
            $output['featured_image'] = elementary_get_featured_image_url($post_id);
		}
	}

	return $output;
}


function elementary_price_data($post_id, $metakey){
    $meta_data = '';
	if ( $post_id && is_numeric( $post_id ) && $metakey && is_string( $metakey ) ) {
		$meta_data =  get_post_meta( $post_id, $metakey, true );
	}

    $output['value'] = $meta_data;
    $output['currency'] = get_woocommerce_currency_symbol();
    return $output;
}
