<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function elementary_posttype_admin_css() {
    global $post_type;
    $post_types = array(
                        /* set post types */
                        'pauple_element',
                  );
    if( 'pauple_element' == $post_type){
        echo '<style type="text/css">.wp-list-table .view{display: none;} .wrap .add-new-h2, .wrap .add-new-h2:active{ color:white; background:#ff4c69}</style>';
    }

}

add_action( 'admin_head-post-new.php', 'elementary_posttype_admin_css' );
add_action( 'admin_head-post.php', 'elementary_posttype_admin_css' );
add_action( 'admin_head-edit.php', 'elementary_posttype_admin_css' );



/** Add Custom column to the Elements management screen */
add_filter( 'manage_edit-pauple_element_columns', 'elementary_add_custom_columns' );

function elementary_add_custom_columns( $columns ) {
    $custom_columns = array(
		'shortcode' => __( 'Shortcode' ),
	);
    $columns = array_merge( $columns, $custom_columns );
	return $columns;
}

/** Make the custom column sortable */
add_filter( 'manage_edit-pauple_element_sortable_columns', 'elementary_sortable_columns' );

function elementary_sortable_columns( $columns ) {
	$columns['shortcode'] = 'shortcode';
	return $columns;
}

/** Add content to the custom column */
add_action( 'manage_pauple_element_posts_custom_column', 'elementary_manage_custom_columns', 10, 2 );

function elementary_manage_custom_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'shortcode' column. */
		case 'shortcode' :

        $shortcode = "[elementary id='" . $post_id . "']";

        echo '<input type="text" onfocus="this.select();" readonly="readonly" value="' . esc_attr( $shortcode ) . '" class="code elementary-shortcode">';
        break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
