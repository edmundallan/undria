<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Add a debug-notification link to the WP Toolbar
function elementary_debug_notification() {
	global $wp_admin_bar;
	$args = array(
		'id' => 'elementary-debug',
		'title'  => esc_html__( 'ELEMENTARY_DEBUG_MODE ON', 'elementary' ),
		'href' => esc_url( ELEMENTARY_PLUGIN_PATH . '/elementary.log' ),
		'meta' => array(
			'title'  => esc_html__( 'ELEMENTARY_DEBUG_MODE is enabled. Click here to view the log file.', 'elementary' ),
            'target' => '_blank',
			)
	);
	$wp_admin_bar->add_node( $args );
}

function elementary_debug_css() {
	echo "<style> #wpadminbar ul#wp-admin-bar-root-default #wp-admin-bar-elementary-debug{ background-color: rgba(200,64,64,1);}#wpadminbar .ab-top-menu>li#wp-admin-bar-elementary-debug:hover>.ab-item,#wpadminbar .ab-top-menu>li#wp-admin-bar-elementary-debug>.ab-item:focus { background-color: rgba(240,20,20,0.5); color: #fff;} </style>";
}

if ( defined( 'ELEMENTARY_DEBUG_MODE' ) && ELEMENTARY_DEBUG_MODE ) {
    add_action('admin_bar_menu', 'elementary_debug_notification', 999);
    add_action('wp_head','elementary_debug_css', 999);
    add_action('admin_head','elementary_debug_css', 999);
}
