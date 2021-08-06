<?php
/*
Plugin Name: Elementary
Plugin URI: http://pauple.com/elementary/
Description: A drag and drop grid builder plugin to make grids, carousels, sliders, masonry and more. Beautifully.
Version: 1.2
Author: Pauple Studios
Author URI: http://www.pauple.com

Text Domain: elementary
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ELEMENTARY_DEBUG_MODE constant - Set to true to enter debug mode.
if ( ! defined( 'ELEMENTARY_DEBUG_MODE' ) ) {
    define( 'ELEMENTARY_DEBUG_MODE' , false );
}
if ( ! defined( 'ELEMENTARY_SANDBOX' ) ) {
    define( 'ELEMENTARY_SANDBOX' , false );
}
if ( ! defined( 'ELEMENTARY_TESTS' ) ) {
    define( 'ELEMENTARY_TESTS' , false );
}

// Set up our WordPress Plugin


// The primary sanity check, automatically disable the plugin on activation if it doesn't
// meet minimum requirements.
function elementary_version_check() {
    if ( version_compare( get_bloginfo( 'version' ), '3.8', '<' ) ) {
        wp_die( "You seem to be using an <a href='https://wordpress.org/about/stats/' target='_blank' title='WordPress Version Statistics'>old version of WordPress</a>. You must <a href='" . admin_url( 'update-core.php') . "' target='_blank' title='Update WordPress'>update WordPress</a> to use this plugin!", "Archaic WordPress", array ('back_link' => true) );
    }
    if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
        wp_die( "You seem to be using an <a href='http://php.net/eol.php' target='_blank' title='Unsupported PHP Versions'>unsupported version of PHP</a>. Contact your webhost to update PHP</a>!", "Archaic PHP", array ('back_link' => true) );
    }
}

if ( !defined( 'ELEMENTARY_PLUGIN_VERSION' ) ) {
    define( 'ELEMENTARY_PLUGIN_VERSION', '1.2' );
}

if ( !defined( 'ELEMENTARY_DB_VERSION' ) ) {
    define( 'ELEMENTARY_DB_VERSION', '2' );
}

//Path to the plugin root file
// ELEMENTARY_PLUGIN_FILE: /wp-content/plugins/elementary/elementary.php

if ( !defined('ELEMENTARY_PLUGIN_FILE') ) {
	define( 'ELEMENTARY_PLUGIN_FILE', __FILE__ );
}

// Plugin Folder Path.
// ELEMENTARY_PLUGIN_DIR: /wp-content/plugins/elementary

if ( ! defined( 'ELEMENTARY_PLUGIN_DIR' ) ) {
	define( 'ELEMENTARY_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Plugin Folder URL.
// ELEMENTARY_PLUGIN_PATH: http://www.example.com/wp-content/plugins/elementary

if ( !defined('ELEMENTARY_PLUGIN_PATH') ) {
	define('ELEMENTARY_PLUGIN_PATH', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

define( 'ELEMENTARY_PLUGIN_IMG_DIR', ELEMENTARY_PLUGIN_PATH . '/assets/images' );
define( 'ELEMENTARY_PLUGIN_STYLESHEET_DIR', ELEMENTARY_PLUGIN_PATH . '/assets/stylesheet' );
define( 'ELEMENTARY_PLUGIN_JS_DIR', ELEMENTARY_PLUGIN_PATH . '/assets/javascript' );
define( 'ELEMENTARY_PLUGIN_CONFIG_DIR', ELEMENTARY_PLUGIN_PATH . '/src/config' );
define( 'ELEMENTARY_PLUGIN_HELPER_DIR', ELEMENTARY_PLUGIN_PATH . '/src/helper' );
define( 'ELEMENTARY_PLUGIN_MODULES_DIR', ELEMENTARY_PLUGIN_PATH . '/src/modules' );
define( 'ELEMENTARY_PLUGIN_PARTIALS_DIR', ELEMENTARY_PLUGIN_PATH . '/src/partials' );
define( 'ELEMENTARY_TESTS_DIR', get_stylesheet_directory_uri() . '/tests' );

add_action( 'admin_menu', 'add_elementary_admin_menu' );

function add_elementary_admin_menu() {

	add_submenu_page( null, 'Elementary', 'Elementary', 'manage_element', 'elementary', 'elementary_admin_page', 'dashicons-schedule', '30.1' );
    if ( defined( 'ELEMENTARY_SANDBOX' ) && ELEMENTARY_SANDBOX ) {
        include('src/wordpress/sandbox1.php');
        include('src/wordpress/sandbox2.php');
    	add_menu_page(  'Pauple Sandbox 1', 'Pauple Sandbox 1', 'manage_options', 'pauple-sandbox1', 'pauple_elementary_sandbox1', 'dashicons-lightbulb', '30.2'  );
    	add_menu_page(  'Pauple Sandbox 2', 'Pauple Sandbox 2', 'manage_options', 'pauple-sandbox2', 'pauple_elementary_sandbox2', 'dashicons-lightbulb', '30.3'  );
    }

}


// Add Settings link on Plugins Screen

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'elementary_add_settings_link' );

function elementary_add_settings_link( $links ) {
	$settings_link = '<a href="edit.php?post_type=element" title="Elementary Settings">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
}


// Make the ajaxurl variable available in the front-end

add_action( 'wp_head', 'elementary_ajaxurl' );
add_action( 'admin_head', 'elementary_ajaxurl' );

function elementary_ajaxurl() {
?>
	<script type="text/javascript">
        var wpPath = {
		    pluginurl: '<?php echo ELEMENTARY_PLUGIN_PATH; ?>',
		    ajaxurl: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            version: '<?php echo ELEMENTARY_PLUGIN_VERSION; ?>'
		};
        
        var AppPayload = 'dummy';
	</script>
<?php
}


// Flush the rewrite rules on activation

register_activation_hook( __FILE__, 'elementary_activation' );

function elementary_activation() {
    elementary_version_check();
    elementary_register_cpt();
    elementary_init_options();
    elementary_maybe_update();
}

function elementary_register_cpt() {
    $cpt = new Elementary_Custom_Post_Type;
	$cpt->register_element();
	flush_rewrite_rules();
}

function elementary_init_options() {
    update_option( 'elementary_plugin_version', ELEMENTARY_PLUGIN_VERSION );
    add_option( 'elementary_db_version', ELEMENTARY_DB_VERSION );
}

function elementary_maybe_update() {
    $elementary_plugin_version = get_option( 'elementary_plugin_version' );

    if ( version_compare( $elementary_plugin_version, '0.32', '<' ) ) {
        global $wordpress_functions;

        $wordpress_functions->update_meta_key('_atom_store', '_atomStore');
        $wordpress_functions->update_meta_key('_content_settings_store', '_contentSettingsStore');
        $wordpress_functions->update_meta_key('_content_store', '_contentStore');
        $wordpress_functions->update_meta_key('_control_tools_store', '_controlToolsStore');
        $wordpress_functions->update_meta_key('_css_store', '_cssStore');
        $wordpress_functions->update_meta_key('_group_settings_store', '_groupSettingsStore');
        $wordpress_functions->update_meta_key('_style_settings_store', '_styleSettingsStore');
    }

    $elementary_db_version = get_option( 'elementary_db_version' );
    if ( $elementary_db_version >= 3 ) {
        return;
    }
    require_once( __DIR__ . '/update.php' );
    elementary_update_db();
}

// Flush the rewrite rules on deactivation

register_deactivation_hook( __FILE__, 'elementary_deactivation' );

function elementary_deactivation() {
	flush_rewrite_rules();
}

include('main-utils.php');


if ( is_elementary_test_mode_enabled() ) {
    include('tests/integration-tests/integration-test-ajax.php');
    include('tests/integration-tests/mock-db-migrate.php');
}
