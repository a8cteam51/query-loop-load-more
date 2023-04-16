<?php

defined( 'ABSPATH' ) || exit;

use WPcomSpecialProjects\Scaffold\Plugin;

// region

/**
 * Returns the plugin's main class instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  Plugin
 */
function wpcomsp_qllm_get_plugin_instance(): Plugin {
	return Plugin::get_instance();
}

/**
 * Returns the plugin's slug.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function wpcomsp_qllm_get_plugin_slug(): string {
	return sanitize_key( WPCOMSP_QLLM_METADATA['TextDomain'] );
}

// endregion

//region OTHERS

require WPCOMSP_QLLM_PATH . 'includes/assets.php';
require WPCOMSP_QLLM_PATH . 'includes/settings.php';

// endregion
