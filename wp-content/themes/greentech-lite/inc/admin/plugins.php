<?php
/**
 * Add required and recommended plugins.
 *
 * @package Greentech Lite
 */

add_action( 'tgmpa_register', 'greentech_lite_register_required_plugins' );

/**
 * Register required plugins
 *
 * @since  1.0
 */
function greentech_lite_register_required_plugins() {
	$plugins = greentech_lite_required_plugins();

	$config = array(
		'id'          => 'greentech-lite',
		'has_notices' => false,
	);

	tgmpa( $plugins, $config );
}

/**
 * List of required plugins
 */
function greentech_lite_required_plugins() {
	return array(
		array(
			'name' => esc_html__( 'Jetpack', 'greentech-lite' ),
			'slug' => 'jetpack',
		),
		array(
			'name' => esc_html__( 'Slim SEO', 'greentech-lite' ),
			'slug' => 'slim-seo',
		),
	);
}
