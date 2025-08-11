<?php
// Minimal test bootstrap - just define required constants and mock WordPress classes

if (!defined('ABSPATH')) {
    define('ABSPATH', '/tmp/');
}

if (!defined('WPCOMSP_QLLM_DIR_PATH')) {
    define('WPCOMSP_QLLM_DIR_PATH', dirname(__DIR__) . '/');
}

// Mock minimal WordPress HTML processor
class WP_HTML_Tag_Processor {
    private $html;

    public function __construct($html) {
        $this->html = $html;
    }

    public function next_tag($args = []) {
        if (isset($args['class_name']) && $args['class_name'] === 'wp-block-query') {
            return strpos($this->html, 'wp-block-query') !== false;
        }
        return false;
    }

    public function set_attribute($name, $value) {
        $this->html = str_replace(
            '<div class="wp-block-query"',
            "<div class=\"wp-block-query\" {$name}=\"{$value}\"",
            $this->html
        );
    }

    public function get_updated_html() {
        return $this->html;
    }
}
