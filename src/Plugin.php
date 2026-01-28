<?php

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedNamespaceFound
namespace WPcomSpecialProjects\Qllm;

defined( 'ABSPATH' ) || exit;

/**
 * WordPress dependencies
 */
use WP_HTML_Tag_Processor;

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
	public function initialize(): void {
		add_filter( 'register_block_type_args', array( $this, 'block_meta' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
		add_filter( 'render_block_core/query', array( $this, 'render_query_block' ), 20 );
	}

	/**
	 * Returns true if all the plugin's dependencies are met.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  true|\WP_Error
	 */
	public function is_active(): bool|\WP_Error {
		return true;
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
		$is_active = $this->is_active();
		if ( is_wp_error( $is_active ) ) {
			wpcomsp_qllm_output_requirements_error( $is_active );
			return;
		}

		$this->initialize();
	}

	/**
	 * Enqueue assets for the plugin.
	 *
	 * @return void
	 */
	public function assets(): void {
		$asset_meta = wpcomsp_qllm_get_asset_meta( WPCOMSP_QLLM_DIR_PATH . 'assets/js/build/frontend.js' );

		wp_register_style(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_DIR_URL . 'assets/js/build/style-index.css',
			array(),
			$asset_meta['version']
		);

		wp_register_script(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_DIR_URL . 'assets/js/build/frontend.js',
			$asset_meta['dependencies'],
			$asset_meta['version'],
			true
		);
	}

	/**
	 * Enqueue assets for the plugin block editor view.
	 *
	 * @return void
	 */
	public function editor_assets(): void {
		$deps = wpcomsp_qllm_get_asset_meta( WPCOMSP_QLLM_DIR_PATH . 'assets/js/build/index.js' );

		wp_enqueue_style(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_DIR_URL . 'assets/js/build/index.css',
			array(),
			$deps['version']
		);

		wp_enqueue_script(
			'wpcomsp-qllm',
			WPCOMSP_QLLM_DIR_URL . 'assets/js/build/index.js',
			$deps['dependencies'],
			$deps['version'],
			true
		);
	}

	/**
	 * Filter the pagination block to add a new attribute and render callback.
	 *
	 * @param array  $settings Array of arguments for registering a block type.
	 * @param string $name     Block type name including namespace.
	 *
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

		// Add a "infinite scroll" attribute that we can toggle in the editor.
		$settings['attributes']['infiniteScroll'] = array(
			'type'    => 'boolean',
			'default' => false,
		);

		// Set the infiniteScroll animation color.
		$settings['attributes']['infiniteScrollColor'] = array(
			'type'    => 'string',
			'default' => '#000',
		);

		// Button text attribute.
		$settings['attributes']['loadMoreText'] = array(
			'type'    => 'string',
			'default' => esc_html__( 'Load More', 'query-loop-load-more' ),
		);

		// Loading text attribute.
		$settings['attributes']['loadingText'] = array(
			'type'    => 'string',
			'default' => esc_html__( 'Loading...', 'query-loop-load-more' ),
		);

		// Update URL.
		$settings['attributes']['updateUrl'] = array(
			'type'    => 'boolean',
			'default' => false,
		);

		// Adds conditional asset loading.
		$settings['style_handles'][]  = 'wpcomsp-qllm';
		$settings['script_handles'][] = 'wpcomsp-qllm';

		return $settings;
	}


	/**
	 * Renders the `core/query-pagination` block on the server.
	 *
	 * @param array     $attributes Block attributes.
	 * @param string    $content    Block default content.
	 * @param \WP_Block $block      Block instance.
	 *
	 * @return string Returns the wrapper for the Query pagination.
	 */
	public function render( array $attributes, string $content, \WP_Block $block ): string {

		// If load more isn't set to be on, return the core pagination.
		if ( false === $attributes['loadMore'] ) {
			return render_block_core_query_pagination( $attributes, $content );
		}

		global $wp_query;

		$arrow_map = array(
			'none'    => '',
			'arrow'   => '→',
			'chevron' => '»',
		);

		// Get query context for current page number and query Id.
		$query_id       = (int) ( $block->context['queryId'] ?? 0 );
		$page_key       = isset( $block->context['queryId'] ) ? 'query-' . $query_id . '-page' : 'query-page';
		$inherit        = $block->context['query']['inherit'] ?? false;
		$is_infinite    = $attributes['infiniteScroll'] ?? false;
		$is_update_url  = $attributes['updateUrl'] ?? false;
		$page_parameter = $inherit ? 'paged' : $page_key;
		// Get current page number.
		// Inherited queries will use the current page number from the global $wp_query object
		// or 1 as a fallback. Non-paged $wp_query objects have the paged attribute set to 0.
		if ( $inherit ) {
			$page = $wp_query->is_paged ? (int) $wp_query->get( 'paged' ) : 1;
			// Custom queries will use the page number based on the query id or 1 as a fallback.
		} else {
			$page = (int) ( $_GET[ $page_key ] ?? 1 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		$block_query      = $inherit ? $wp_query : new \WP_Query( build_query_vars_from_query_block( $block, $page ) );
		$max_pages        = empty( $block->context['query']['pages'] ) ? $block_query->max_num_pages : (int) $block->context['query']['pages'];
		$button_classes   = $is_infinite
			? 'wp-load-more__button wp-load-more__infinite-scroll'
			: 'wp-block-button__link wp-element-button wp-load-more__button';
		$pagination_arrow = 'none' !== $attributes['paginationArrow'] ? '<span class="wp-block-query-pagination__arrow">' . $arrow_map[ $attributes['paginationArrow'] ] . '</span>' : '';

		$infinite_scroll_markup = '';
		if ( $is_infinite ) {
			$infinite_scroll_markup = '
					<div class="animation-wrapper" style="border-color: ' . esc_attr( $attributes['infiniteScrollColor'] ) . '">
						<div></div>
						<div></div>
					</div>
			';
		}

		// more posts available
		if ( $page < $max_pages ) {

			// Build list of load more links.
			$block_content = sprintf(
				'<a class="%1$s" href="?%2$s=%3$d" data-query-next-page="%3$d" data-query-key="%5$d" data-query-max-page="%6$d" data-query-url="%2$s" data-update-url="%7$s"><span class="qllm-loading">%4$s%9$s</span><span class="qllm-load-more%10$s">%8$s</span></a>',
				$button_classes,
				$page_parameter,
				$page + 1,
				$is_infinite ? '' : esc_html( $attributes['loadingText'] ),
				$query_id,
				$max_pages,
				$is_update_url,
				$is_infinite ? esc_html( $attributes['loadMoreText'] ) : esc_html( $attributes['loadMoreText'] ) . $pagination_arrow,
				$infinite_scroll_markup,
				$is_infinite ? ' screen-reader-text' : '' // Note space at the beginning of the class name
			);
		} else {
			// All posts loaded.
			return '';
		}

		return '
			<div class="is-layout-flex wp-block-buttons">
				<div class="wp-block-button aligncenter">
					' . wp_kses_post( $block_content ) . '
				</div>
			</div>
		';
	}

	/**
	 * Add region-router attribute to the query block.
	 *
	 * @param string $block_content The block content.
	 *
	 * @return string
	 */
	public function render_query_block( $block_content ) {
		static $region_counter = 1;

		$p = new WP_HTML_Tag_Processor( $block_content );

		if ( $p->next_tag( array( 'class_name' => 'wp-block-query' ) ) ) {
			$p->set_attribute( 'data-qllm-query-region', $region_counter++ );
			$block_content = $p->get_updated_html();
		}

		return $block_content;
	}
}
