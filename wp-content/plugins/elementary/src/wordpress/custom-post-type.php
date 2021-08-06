<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementary_Custom_Post_Type' ) ) :
	class Elementary_Custom_Post_Type {

	    public $post_type_name = 'pauple_element';

        public function __construct() {
            add_action( 'init', array( $this, 'register_element' ) );
        }

        public function register_element() {

            $labels = array(
                'name'						=> 'Elements',
                'singular_name'				=> 'Element',
                'add_new'					=> 'Add New Element',
                'add_new_item'				=> 'Add New Element',
                'edit'						=> 'Edit',
                'edit_item'					=> 'Edit Element',
                'new_item'					=> 'New Element',
                'view_item'					=> 'View Element',
                'search_items'				=> 'Search Elements',
                'not_found'					=> 'No Elements found',
                'parent'					=> 'Parent Element',
                'filter_items_list'			=> 'Filter elements list',
                'items_list'				=> 'Elements list',
                'items_list_navigation'		=> 'Elements list navigation'
        	);

        	$args = array(
        		'labels'					=> $labels,
        	    'public'					=> true,
        	    'menu_position'				=> 30.1,
        	    'menu_icon'					=> 'dashicons-schedule',
        	    'show_in_nav_menus'			=> false,
				'capability_type'			=> array( 'element', 'elements' ),
				'map_meta_cap'				=> true,
                'supports'					=> array( 'title' ),
                'rewrite'                   => true
        	);

	    	register_post_type( $this->post_type_name, $args );

        }
    }
endif;

$cpt = new Elementary_Custom_Post_Type;
