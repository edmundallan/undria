<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function elementary_enqueue_scripts() {
    global $wordpress_functions;

	$current_screen = get_current_screen();
	if( $current_screen->id === "admin_page_elementary" ) {
		wp_enqueue_style('admin-ui', ELEMENTARY_PLUGIN_STYLESHEET_DIR . '/style.css', '', ELEMENTARY_PLUGIN_VERSION);
		wp_enqueue_script('admin-ui', ELEMENTARY_PLUGIN_JS_DIR . '/script.js', array('jquery'), ELEMENTARY_PLUGIN_VERSION, true);
        wp_enqueue_script('admin-ui-jquery-color', ELEMENTARY_PLUGIN_PATH . '/vendor/jquery.color.js', array('jquery'), ELEMENTARY_PLUGIN_VERSION, true);
        wp_enqueue_script('elementary-mainjs', ELEMENTARY_PLUGIN_PATH . '/app.bundle.js', array('jquery'), ELEMENTARY_PLUGIN_VERSION, true);
	}
	if( $current_screen->id === "pauple_element" ) {
		wp_enqueue_script('element-editor', ELEMENTARY_PLUGIN_JS_DIR . '/element-editor.js', array('jquery'), ELEMENTARY_PLUGIN_VERSION, true);
		wp_enqueue_style('element-editor', ELEMENTARY_PLUGIN_STYLESHEET_DIR . '/element-editor.css', '', ELEMENTARY_PLUGIN_VERSION);
	}
	if( $current_screen->id === "edit-pauple_element" ) {
		wp_enqueue_style('edit-element', ELEMENTARY_PLUGIN_STYLESHEET_DIR . '/edit-element.css', '', ELEMENTARY_PLUGIN_VERSION);
	}
	if( $current_screen->id === "plugins_page_elementary-license" ) {
		wp_enqueue_style('elementary-license', ELEMENTARY_PLUGIN_STYLESHEET_DIR . '/elementary-license.css', '', ELEMENTARY_PLUGIN_VERSION);
	}

	wp_enqueue_script('elementary-notice-update', ELEMENTARY_PLUGIN_JS_DIR . '/notice-update.js', array('jquery'), ELEMENTARY_PLUGIN_VERSION, true);
}

add_action('admin_enqueue_scripts', 'elementary_enqueue_scripts');


function elementary_enqueue_frontend_scripts() {
	wp_enqueue_style('elementary-frontend-ui', ELEMENTARY_PLUGIN_STYLESHEET_DIR . '/elementary-frontend.css', '', ELEMENTARY_PLUGIN_VERSION);
	wp_enqueue_script('masonry-script', ELEMENTARY_PLUGIN_PATH . '/vendor/masonry.pkgd.min.js', array('jquery'), '3.3.2', true);
}

add_action('wp_enqueue_scripts', 'elementary_enqueue_frontend_scripts');


function elementary_admin_page() {
    global $wordpress_functions;
	if ( ! array_key_exists( '_wpnonce', $_REQUEST ) ) {
		$wordpress_functions->elementary_log('"Nonce missing during Element Customization page visit"');
		wp_die( "Wrong Origin! You must <a href='" . wp_login_url() . "' title='Login'>login again</a> to proceed further!", "Elementary Error" );
	}
	else {
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'customize_element' ) ) {
			$wordpress_functions->elementary_log('"Invalid Nonce during Element Customization page visit"');
		    wp_die( "Wrong Origin! You must <a href='" . wp_login_url() . "' title='Login'>login again</a> to proceed further!", "Elementary Error" );
		}
		else {
		?>

            <div id="tool-tip-container"></div>
			<div id="element-save-status">
				<div class="notice notice-success is-dismissible">
				   <p>Element Updated Successfully</p>
				</div>
				<div class="notice notice-error is-dismissible">
				   <p>Element could not be saved.</p>
				</div>
			</div>
            <div id="atomic-overlay-container"></div>
			<div id="app-container">
                <div id="elementary">
		        </div>
            </div>

		<?php
		}
	}
}
