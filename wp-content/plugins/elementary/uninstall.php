<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove the following options from database on uninstalling the Plugin.
$elementary_options = array(
    'elementary_plugin_version',
    'elementary_license_key',
    'elementary_license_status',
);

foreach ( $elementary_options as $option ) {
    delete_option( $option );
}
