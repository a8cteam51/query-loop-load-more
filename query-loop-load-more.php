<?php
/**
 * Plugin Name:             Query Loop Load More
 * Plugin URI:              https://github.com/a8cteam51/query-loop-load-more
 * Description:             Adds a load more option to the Query Loop Pagination block in Gutenberg.
 * Version:                 0.0.2
 * Requires at least:       6.2
 * Tested up to:            6.2
 * Requires PHP:            8.0
 * Author:                  WordPress.com Special Projects
 * Author URI:              https://wpspecialprojects.wordpress.com
 * License:                 GPL v3 or later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:             wpcomsp-qllm
 * Domain Path:             /languages
 **/

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
function_exists( 'get_plugin_data' ) || require_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'WPCOMSP_QLLM_METADATA', get_plugin_data( __FILE__, false, false ) );

define( 'WPCOMSP_QLLM_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPCOMSP_QLLM_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPCOMSP_QLLM_URL', plugin_dir_url( __FILE__ ) );

// Load plugin translations so they are available even for the error admin notices.
add_action(
	'init',
	static function() {
		load_plugin_textdomain(
			WPCOMSP_QLLM_METADATA['TextDomain'],
			false,
			dirname( WPCOMSP_QLLM_BASENAME ) . WPCOMSP_QLLM_METADATA['DomainPath']
		);
	}
);

// Load the autoloader.
if ( ! is_file( WPCOMSP_QLLM_PATH . 'vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function() {
			$message      = __( 'It seems like <strong>Query Loop Load More</strong> is corrupted. Please reinstall!', 'wpcomsp-qllm' );
			$html_message = wp_sprintf( '<div class="error notice wpcomsp-qllm-error">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}
	);
	return;
}
require_once WPCOMSP_QLLM_PATH . 'vendor/autoload.php';

// Initialize the plugin if system requirements check out.
$wpcomsp_qllm_requirements = validate_plugin_requirements( WPCOMSP_QLLM_BASENAME );
define( 'WPCOMSP_QLLM_REQUIREMENTS', $wpcomsp_qllm_requirements );

if ( $wpcomsp_qllm_requirements instanceof WP_Error ) {
	add_action(
		'admin_notices',
		static function() use ( $wpcomsp_qllm_requirements ) {
			$html_message = wp_sprintf( '<div class="error notice wpcomsp-qllm-error">%s</div>', $wpcomsp_qllm_requirements->get_error_message() );
			echo wp_kses_post( $html_message );
		}
	);
} else {
	require_once WPCOMSP_QLLM_PATH . 'functions.php';
	add_action( 'plugins_loaded', array( wpcomsp_qllm_get_plugin_instance(), 'initialize' ) );
}
