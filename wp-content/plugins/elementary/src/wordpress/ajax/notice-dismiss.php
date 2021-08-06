<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_elementary_dismiss_license_notice', 'elementary_dismiss_license_notice' );

function elementary_dismiss_license_notice() {

    if ( current_user_can( 'administrator' ) ) {

		update_option( 'elementary_license_notice_dismissed', 1 );

    }

}
