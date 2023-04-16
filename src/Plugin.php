<?php

namespace WPcomSpecialProjects\Scaffold;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class Plugin {

	/**
	 * Plugin constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function __construct() {
		/* Empty on purpose. */
	}

	/**
	 * Prevent cloning.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	private function __clone() {
		/* Empty on purpose. */
	}

	/**
	 * Prevent unserializing.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function __wakeup() {
		/* Empty on purpose. */
	}

	/**
	 * Returns the singleton instance of the plugin.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  Plugin
	 */
	public static function get_instance(): self {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Initializes the plugin components.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	protected function initialize(): void {
		add_filter( 'register_block_type_args', array( $this, 'block_meta' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
	}

	/**
	 * Initializes the plugin components if WooCommerce is activated.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function maybe_initialize(): void {
		$this->initialize();
	}

	/**
	 * Enqueue assets for the plugin.
	 *
	 * @return void
	 */
	public function assets(): void {

		$deps = include_once WPCOMSP_QLLM_PATH . 'assets/js/build/frontend.asset.php';

		wp_enqueue_style(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_URL . 'assets/js/build/style-index.css',
			array(),
			$deps['version']
		);

		wp_enqueue_script(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_URL . 'assets/js/build/frontend.js',
			array( 'jquery' ),
			$deps['version'],
			true
		);

	}

	/**
	 * Enqueue assets for the plugin block editor view.
	 *
	 * @return void
	 */
	public function editor_assets(): void {

		$deps = include_once WPCOMSP_QLLM_PATH . 'assets/js/build/index.asset.php';

		wp_enqueue_style(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_URL . 'assets/js/build/index.css',
			array(),
			$deps['version']
		);

		wp_enqueue_script(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_URL . 'assets/js/build/index.js',
			$deps['dependencies'],
			$deps['version'],
			true
		);
	}

	/**
	 * Filter the pagination block to add a new attribute and render callback.
	 *
	 * @param array $settings
	 * @param array $metadata
	 * @return array
	 */
	public function block_meta( array $settings, string $name ): array {

		// Check this is the query pagination block, and that the standard WP function exists for a fallback.
		if ( 'core/query-pagination' !== $name || ! function_exists( 'render_block_core_query_pagination' ) ) {
			return $settings;
		}

		// Add a new render callback function to the pagination block.
		$settings['render_callback'] = array( $this, 'render' );

		// Add a "load more" attribute that we can toggle in the editor.
		$settings['attributes']['loadMore'] = array(
			'type'    => 'boolean',
			'default' => false,
		);

		// Button text attribute.
		$settings['attributes']['loadMoreText'] = array(
			'type'    => 'string',
			'default' => __( 'Load More', 'wpcomsp-qllm' ),
		);

		// Loading text attribute.
		$settings['attributes']['loadingText'] = array(
			'type'    => 'string',
			'default' => __( 'Loading...', 'wpcomsp-qllm' ),
		);

		return $settings;
	}


	/**
	 * Renders the `core/query-pagination` block on the server.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block default content.
	 * @param WP_Block $block      Block instance.
	 * @return string Returns the wrapper for the Query pagination.
	 */
	public function render( array $attributes, string $content, \WP_Block $block ): string {

		// If load more isn't set to be on, return the core pagination.
		if ( false === $attributes['loadMore'] ) {
			return render_block_core_query_pagination( $attributes, $content );
		}

		// Get query context for current page number and query Id.
		$page_key    = isset( $block->context['queryId'] ) ? 'query-' . $block->context['queryId'] . '-page' : 'query-page';
		$page        = empty( $_GET[ $page_key ] ) ? 1 : (int) $_GET[ $page_key ]; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$block_query = new \WP_Query( build_query_vars_from_query_block( $block, $page ) );
		$buttons     = '';

		// Build list of load more links.
		for ( $i = $page + 1; $i <= $block_query->max_num_pages; $i++ ) {
			$buttons .= sprintf(
				'<a class="%s" href="%s" data-loading-text="%s">%s</a>',
				'wp-block-button__link wp-element-button wp-load-more__button',
				'?' . $page_key . '=' . $i,
				$attributes['loadingText'],
				$attributes['loadMoreText']
			);
		}

		return '
			<div class="is-layout-flex wp-block-buttons">
				<div class="wp-block-button aligncenter">
					' . wp_kses_post( $buttons ) . '
				</div>
			</div>
		';
	}
}
