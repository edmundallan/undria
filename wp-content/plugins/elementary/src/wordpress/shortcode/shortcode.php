<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// -------------------------------------------------------
// Title: Element Shortcode
// Desc: Displays the element as designed in the backend. Takes post id as argument.
// Usage: [elementary id='25']
// -------------------------------------------------------


if ( ! class_exists( 'Elementary_Shortcodes' ) ) :
    class Elementary_Shortcodes extends Elementary_Shortcode_Helper {

        public $shortcode = 'elementary';

        public function __construct() {
            add_shortcode( $this->shortcode,  array( $this, 'shortcode_function' ) );
        }

        public function shortcode_function( $atts ) {

            global $wordpress_functions;
            $elementary_config = new Elementary_Config();

        	$args = shortcode_atts(
        		array(
        			'id'		=> ''
        		), $atts );

            $post_id = $shortcode_output = '';
            $atoms_list = $content_settings_store = $content_settings = $field_value_array = $post_id_array = $atom_type_array = $content_list = $group_settings_store = $carousel_store = $render_element_payload = $media_data = $media_queries = $css_data = array();

            $post_id = $args['id'];

            if ( $post_id && is_numeric( $post_id ) ) {

                $atoms_list = $this->get_atoms_list( $post_id );

                $carousel_store = $this->get_carousel_store( $post_id );

                $content_settings_store = $this->get_content_settings_store( $post_id );

                $content_settings = $this->get_content_settings( $content_settings_store );
                if ( array_key_exists( 'field_value', $content_settings ) ) {
                    $field_value_array = $content_settings['field_value'];
                }
                if ( array_key_exists( 'post_id_array', $content_settings ) ) {
                    $post_id_array = $content_settings['post_id_array'];
                }
                $atom_type_array = $this->get_atom_type_array( $content_settings_store );
                $content_list = $wordpress_functions->get_data( $atom_type_array, $field_value_array, $post_id_array );

                $group_settings_store = $this->get_group_settings_store( $post_id );

                $shortcode_output .= $elementary_config->get_wrapper( $post_id );

                $render_element_payload = array(
                    'atoms_list' => $atoms_list,
                    'carousel_store' => $carousel_store,
                    'content_list' => $content_list,
                    'content_settings_store' => $content_settings_store,
                    'group_settings_store' => $group_settings_store,
                    'post_id' => $post_id,
                );

                $shortcode_output .= $this->render_element( $render_element_payload );

                $shortcode_output .= '</div>';

                // $media_data = $this->get_media_data();
                //
                // if ( $media_data && ( is_array( $media_data ) ) ) {
                //     $media_queries = $this->get_media_queries( $media_data );
                // }
                //
                // $css_data = $this->get_frontend_css( $post_id );
                // if ( $css_data && ( is_array( $css_data ) ) ) {
                //     $shortcode_output .= $this->construct_css( $css_data, $media_queries );
                // }

                return $shortcode_output;
            }
            else {
                $wordpress_functions->elementary_log( 'Incorrect Post ID entered in the Shortcode: ' . $post_id );
            }
        }

    }
endif;

$shortcodes = new Elementary_Shortcodes();
