<?php
/**
 * Plugin Name:             Query Loop Load More
 * Plugin URI:              https://github.com/a8cteam51/query-loop-load-more
 * Description:             Adds a load more option to the Query Loop Pagination block in Gutenberg.
 * Short Description:       Adds a load more option to the Query Loop Pagination block, allowing users to load more posts without a page refresh.
 * Version:                 1.0.16
 * Requires at least:       6.2
 * Tested up to:            6.8.2
 * Requires PHP:            8.0
 * Author:                  WordPress.com Special Projects
 * Author URI:              https://wpspecialprojects.wordpress.com
 * License:                 GPLv3 or later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:             query-loop-load-more
 * Domain Path:             /languages
 *
 * @package                 Query Loop Load More
 **/

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
define( 'WPCOMSP_QLLM_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPCOMSP_QLLM_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPCOMSP_QLLM_DIR_URL', plugin_dir_url( __FILE__ ) );

// Load the rest of the bootstrap functions.
require_once WPCOMSP_QLLM_DIR_PATH . '/functions-bootstrap.php';

// Load plugin translations so they are available even for the error admin notices.
add_action(
	'init',
	static function () {
		load_plugin_textdomain(
			wpcomsp_qllm_get_plugin_metadata( 'TextDomain' ),
			false,
			dirname( WPCOMSP_QLLM_BASENAME ) . wpcomsp_qllm_get_plugin_metadata( 'DomainPath' )
		);
	}
);

// Load the autoloader.
if ( ! is_file( WPCOMSP_QLLM_DIR_PATH . '/vendor/autoload.php' ) ) {
	wpcomsp_qllm_output_requirements_error( new WP_Error( 'missing_autoloader' ) );
	return;
}
require_once WPCOMSP_QLLM_DIR_PATH . '/vendor/autoload.php';

// Bootstrap the plugin (maybe)!
define( 'WPCOMSP_QLLM_REQUIREMENTS', wpcomsp_qllm_validate_requirements() );
if ( is_wp_error( WPCOMSP_QLLM_REQUIREMENTS ) ) {
	wpcomsp_qllm_output_requirements_error( WPCOMSP_QLLM_REQUIREMENTS );
} else {
	require_once WPCOMSP_QLLM_DIR_PATH . '/functions.php';
	add_action( 'plugins_loaded', array( wpcomsp_qllm_get_plugin_instance(), 'maybe_initialize' ) );
}
