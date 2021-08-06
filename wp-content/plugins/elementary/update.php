<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Run the incremental updates one by one.
*
* For example, if the current DB version is 3, and the target DB version is 6,
* this function will execute update routines if they exist:
*  - elementary_update_routine_4()
*  - elementary_update_routine_5()
*  - elementary_update_routine_6()
*/
function elementary_update_db() {
    // no PHP timeout for running updates
    set_time_limit( 0 );


    // this is the current database schema version number
    $current_db_ver = get_option( 'elementary_db_version' );

    // this is the target version that we need to reach
    $target_db_ver = 3;

    // run update routines one by one until the current version number
    // reaches the target version number
    while ( $current_db_ver < $target_db_ver ) {
        // increment the current db_ver by one
        $current_db_ver ++;

        // each db version will require a separate update function
        // for example, for db_ver 3, the function name should be elementary_update_routine_3
        $func = "elementary_update_routine_{$current_db_ver}";
        if ( function_exists( $func ) ) {
            call_user_func( $func );
        }

        // update the option in the database, so that this process can always
        // pick up where it left off
        update_option( 'elementary_db_version', $current_db_ver );
    }
}

/**
* Change keys for the `fieldsList`, `fieldsListOL` and `atomTypeArray` in the `contentSettingsStore`.
*/
function elementary_update_routine_2() {

    $shortcode_helper = new Elementary_Shortcode_Helper();

    // Fetch the IDs from all elements
    $args = array(
        'posts_per_page'    => -1,
        'post_type'         => 'element',
        'post_status'       => 'any',
        'fields'            => 'ids',
    );
    $element_ids = get_posts( $args );

    foreach ( $element_ids as $post_id ) {

        // Migrate contentSettingsStore
        $content_settings_store = $shortcode_helper->get_content_settings_store( $post_id );

        $options_array = array( 'fieldsList', 'fieldsListOL', 'atomTypeArray' );

        foreach ( $options_array as $key => $value ) {
            if ( $content_settings_store && ( is_array( $content_settings_store ) ) ) {
                if ( array_key_exists( $options_array[$key], $content_settings_store ) ) {
                    $content_settings_store[$options_array[$key]] = elementary_migrate( $content_settings_store[$options_array[$key]] );
                }
            }
        }

        update_post_meta( $post_id, '_contentSettingsStore', $content_settings_store );


        // Migrate atomStore
        $atoms_list = $shortcode_helper->get_atoms_list( $post_id );

        if ( array_key_exists( 1, $atoms_list ) ) {
            if ( array_key_exists( 'editableFields', $atoms_list[1] ) ) {
                $atoms_list[1]['editableFields'] = elementary_migrate( $atoms_list[1]['editableFields'] );
            }
        }
        $atoms_list = elementary_list_migrate( $atoms_list, 'dataFieldID' );

        $atom_store['storeAtoms'] = $atoms_list;
        update_post_meta( $post_id, '_atomStore', $atom_store );
    }
}

function elementary_update_routine_3() {

    $new_post_type = 'pauple_element';
    // Fetch the IDs from all elements
    $args = array(
        'posts_per_page'    => -1,
        'post_type'         => 'element',
        'post_status'       => 'any',
        'fields'            => 'ids',
    );
    $element_ids = get_posts( $args );

    elementary_register_cpt();

    foreach ( $element_ids as $post_id ) {
        set_post_type( $post_id, $new_post_type );
    }

}

function elementary_migrate( $input_array ) {
    $output_array = array();
    $input_array = (array) $input_array;	// Typecast to Array
    $input_array_keys = array_keys( $input_array );
    $count = 1;
    foreach ( $input_array_keys as $key => $value ) {
        $output_array['field' . $count] = $input_array[$input_array_keys[$key]];
        $count++;
    }
    return $output_array;
}

function elementary_list_migrate( $list, $field ) {
    $count = 1;
    foreach ( $list as $key => $value ) {
        if ( array_key_exists( $field, $value ) ) {
            $list[$key][$field] = 'field' . $count;
            $count++;
        }
    }
    return $list;
}
