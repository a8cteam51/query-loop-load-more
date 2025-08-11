<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class MultipleQueryRegionTest extends TestCase {

    private $plugin;

    public function setUp(): void {
        require_once __DIR__ . '/../src/Plugin.php';
        $this->plugin = \WPcomSpecialProjects\Qllm\Plugin::get_instance();
    }

    private function createMockBlock($context) {
        return new class($context) {
            public $context;
            public function __construct($context) { $this->context = $context; }
        };
    }

    public function testMultipleQueriesRegionMismatch() {
        // Initial page load - two queries
        $stickyBlock = $this->createMockBlock(['query' => ['sticky' => 'only'], 'queryId' => 1]);
        $normalBlock = $this->createMockBlock(['query' => ['sticky' => 'exclude'], 'queryId' => 2]);

        $html = '<div class="wp-block-query"><div class="wp-block-post-template"></div></div>';

        $stickyResult = $this->plugin->render_query_block($html);
        $normalResult = $this->plugin->render_query_block($html);

        preg_match('/data-qllm-query-region="([^"]*)"/', $normalResult, $matches);
        $originalRegion = $matches[1] ?? null;

        // Fresh plugin instance simulates AJAX request (static counters reset)
        $freshPlugin = \WPcomSpecialProjects\Qllm\Plugin::get_instance();
        $ajaxResult = $freshPlugin->render_query_block($html);
        preg_match('/data-qllm-query-region="([^"]*)"/', $ajaxResult, $ajaxMatches);
        $ajaxRegion = $ajaxMatches[1] ?? null;

        $this->assertNotNull($originalRegion, "Original region not found");
        $this->assertNotNull($ajaxRegion, "AJAX region not found");

        $this->assertEquals($originalRegion, $ajaxRegion,
            "Region mismatch: original={$originalRegion}, ajax={$ajaxRegion}");
    }

    public function testDuplicatedBlocksConflict() {
        $block1 = $this->createMockBlock(['queryId' => 1]);
        $block2 = $this->createMockBlock(['queryId' => 1]);

        $html = '<div class="wp-block-query"><div class="wp-block-post-template"></div></div>';

        $result1 = $this->plugin->render_query_block($html);
        $result2 = $this->plugin->render_query_block($html);

        preg_match('/data-qllm-query-region="([^"]*)"/', $result1, $matches1);
        preg_match('/data-qllm-query-region="([^"]*)"/', $result2, $matches2);

        $region1 = $matches1[1] ?? null;
        $region2 = $matches2[1] ?? null;

        $this->assertNotNull($region1, "Region 1 not found");
        $this->assertNotNull($region2, "Region 2 not found");

        // This should fail with current implementation
        $this->assertNotEquals($region1, $region2,
            "Duplicate blocks have same region: {$region1}");
    }
}
