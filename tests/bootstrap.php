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
        if (isset($args['class_name'])) {
            return strpos($this->html, $args['class_name']) !== false;
        }
        return false;
    }

    public function set_attribute($name, $value) {
        if (strpos($this->html, 'wp-block-post-template') !== false) {
            $this->html = str_replace(
                '<div class="wp-block-post-template"',
                "<div class=\"wp-block-post-template\" {$name}=\"{$value}\"",
                $this->html
            );
        }
    }

    public function get_updated_html() {
        return $this->html;
    }
}
