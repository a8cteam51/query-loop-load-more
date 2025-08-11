<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class MultipleQueryRegionTest extends TestCase {

    private $plugin;

    public function setUp(): void {
        require_once __DIR__ . '/../src/Plugin.php';
        $this->plugin = \WPcomSpecialProjects\Qllm\Plugin::get_instance();
    }

    private function createStickyPostsBlock($queryId) {
        return [
            'attrs' => [
                'queryId' => $queryId
            ],
            'context' => [
                'queryId' => $queryId,
                'query' => [
                    'perPage' => 1,
                    'pages' => 0,
                    'offset' => 0,
                    'postType' => 'post',
                    'order' => 'desc',
                    'orderBy' => 'date',
                    'author' => '',
                    'search' => '',
                    'exclude' => [],
                    'sticky' => 'only',
                    'inherit' => false,
                    'taxQuery' => null,
                    'parents' => []
                ]
            ]
        ];
    }

    private function createNormalPostsBlock($queryId) {
        return [
            'attrs' => [
                'queryId' => $queryId
            ],
            'context' => [
                'queryId' => $queryId,
                'query' => [
                    'perPage' => 10,
                    'pages' => 0,
                    'offset' => 0,
                    'postType' => 'post',
                    'order' => 'desc',
                    'orderBy' => 'date',
                    'author' => '',
                    'search' => '',
                    'exclude' => [],
                    'sticky' => 'exclude',
                    'inherit' => false,
                    'taxQuery' => null,
                    'parents' => []
                ]
            ]
        ];
    }

    public function testDifferentQueryIdsGetDifferentRegions() {
        // Test case for: https://wordpress.org/support/topic/load-more-only-loads-one-of-the-next-pages/
        $stickyBlock = $this->createStickyPostsBlock(1);
        $normalBlock = $this->createNormalPostsBlock(2);

        $stickyHtml = '<div class="wp-block-query"><div class="wp-block-post-template"><!-- wp:post-title /--><!-- wp:post-excerpt /--></div></div>';
        $normalHtml = '<div class="wp-block-query"><div class="wp-block-post-template"><!-- wp:post-title /--><!-- wp:post-excerpt /--></div><div class="wp-block-query-pagination"></div></div>';

        // Simulate initial page render: both queries rendered
        $stickyResult = $this->plugin->render_query_block($stickyHtml, $stickyBlock);
        $normalResult = $this->plugin->render_query_block($normalHtml, $normalBlock);

        // Simulate pagination click: only normal posts query re-rendered
        $normalPage2Result = $this->plugin->render_query_block($normalHtml, $normalBlock);

        // Simulate second pagination click (page 3) - user reported: "If you click it again, it loads nothing"
        $normalPage3Result = $this->plugin->render_query_block($normalHtml, $normalBlock);

        // Extract region values
        preg_match('/data-qllm-query-region="([^"]*)"/', $stickyResult, $stickyMatches);
        preg_match('/data-qllm-query-region="([^"]*)"/', $normalResult, $normalMatches);
        preg_match('/data-qllm-query-region="([^"]*)"/', $normalPage2Result, $normalPage2Matches);
        preg_match('/data-qllm-query-region="([^"]*)"/', $normalPage3Result, $normalPage3Matches);

        $stickyRegion = $stickyMatches[1] ?? null;
        $normalRegion = $normalMatches[1] ?? null;
        $normalPage2Region = $normalPage2Matches[1] ?? null;
        $normalPage3Region = $normalPage3Matches[1] ?? null;

        $this->assertNotNull($stickyRegion, "Sticky posts region not found");
        $this->assertNotNull($normalRegion, "Normal posts region not found");
        $this->assertNotNull($normalPage2Region, "Normal posts page 2 region not found");
        $this->assertNotNull($normalPage3Region, "Normal posts page 3 region not found");

        // Different queryIds should get different regions (static counter ignores queryId)
        $this->assertNotEquals($stickyRegion, $normalRegion, "Different queryIds should get different regions");
        $this->assertNotEquals($normalRegion, $normalPage2Region, "Page 1 to 2 should get different regions");
        $this->assertNotEquals($normalPage2Region, $normalPage3Region, "Page 2 to 3 should get different regions");
        $this->assertNotEquals($stickyRegion, $normalPage3Region, "Sticky vs page 3 should get different regions");
    }

    public function testSameQueryIdsStillGetDifferentRegions() {
        // Edge case for: https://wordpress.org/support/topic/load-more-only-loads-one-of-the-next-pages/
        // Test: Both queries have same queryId:1 (WordPress duplication bug) but should still get different regions
        $stickyBlock = $this->createStickyPostsBlock(1);
        $normalBlock = $this->createNormalPostsBlock(1); // Same queryId!

        $stickyHtml = '<div class="wp-block-query"><div class="wp-block-post-template"><!-- wp:post-title /--><!-- wp:post-excerpt /--></div></div>';
        $normalHtml = '<div class="wp-block-query"><div class="wp-block-post-template"><!-- wp:post-title /--><!-- wp:post-excerpt /--></div><div class="wp-block-query-pagination"></div></div>';

        // Simulate initial page render: both queries with same queryId
        $stickyResult = $this->plugin->render_query_block($stickyHtml, $stickyBlock);
        $normalResult = $this->plugin->render_query_block($normalHtml, $normalBlock);

        // Simulate pagination click: only normal posts query re-rendered
        $normalPage2Result = $this->plugin->render_query_block($normalHtml, $normalBlock);

        // Simulate second pagination click (page 3) - user reported: "If you click it again, it loads nothing"
        $normalPage3Result = $this->plugin->render_query_block($normalHtml, $normalBlock);

        // Extract region values
        preg_match('/data-qllm-query-region="([^"]*)"/', $stickyResult, $stickyMatches);
        preg_match('/data-qllm-query-region="([^"]*)"/', $normalResult, $normalMatches);
        preg_match('/data-qllm-query-region="([^"]*)"/', $normalPage2Result, $normalPage2Matches);
        preg_match('/data-qllm-query-region="([^"]*)"/', $normalPage3Result, $normalPage3Matches);

        $stickyRegion = $stickyMatches[1] ?? null;
        $normalRegion = $normalMatches[1] ?? null;
        $normalPage2Region = $normalPage2Matches[1] ?? null;
        $normalPage3Region = $normalPage3Matches[1] ?? null;

        $this->assertNotNull($stickyRegion, "Sticky posts region not found");
        $this->assertNotNull($normalRegion, "Normal posts region not found");
        $this->assertNotNull($normalPage2Region, "Normal posts page 2 region not found");
        $this->assertNotNull($normalPage3Region, "Normal posts page 3 region not found");

        // Same queryIds should STILL get different regions (plugin ignores queryId)
        $this->assertNotEquals($stickyRegion, $normalRegion, "Same queryId should still get different regions");
        $this->assertNotEquals($normalRegion, $normalPage2Region, "Same queryId page 1 to 2 should get different regions");
        $this->assertNotEquals($normalPage2Region, $normalPage3Region, "Same queryId page 2 to 3 should get different regions");
        $this->assertNotEquals($stickyRegion, $normalPage3Region, "Same queryId sticky vs page 3 should get different regions");
    }
}
