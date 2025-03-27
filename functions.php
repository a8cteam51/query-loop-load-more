<?php

defined( 'ABSPATH' ) || exit;

use WPcomSpecialProjects\Qllm\Plugin;

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

require WPCOMSP_QLLM_DIR_PATH . 'includes/assets.php';
