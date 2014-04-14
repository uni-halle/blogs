<?php

/**
 * Provides an [raw] shortoce that attempts to fix a theme conflict with
 * themes based on ThemeForest framework.
 *
 * More info:
 * http://theandystratton.com/2011/shortcode-autoformatting-html-with-paragraphs-and-line-breaks
 * https://github.com/drodenbaugh/awpcp/issues/312#issuecomment-9582286
 */
class AWPCP_RawShortcode {

    private $raw = array();

    public function __construct() {
        add_action('init', array($this, 'init'), 10000);
    }

    public function init() {
        global $wp_filter;

        // known names for the formatter function
        $functions = array('my_formatter', 'theme_formatter', 'columns_formatter');
        $function = false;

        foreach ( $functions as $fn ) {
            if ( function_exists( $fn ) ) {
                $function = $fn;
                break;
            }
        }

        // do nothing if we can't find one of the problematic functions
        if ( false === $function ) return;

        // remove ThemesForest formater filter
        remove_filter('the_content', $function, 99);

        // TODO: use awpcp_insert_before
        $the_content = $wp_filter['the_content'][10];
        // we want default filters to run in the default order
        // see wp-includes/default-filters.php
        $wp_filter['the_content'][10] = array();
        add_filter('the_content', 'wptexturize');
        add_filter('the_content', 'convert_smilies');
        add_filter('the_content', 'convert_chars');
        add_filter('the_content', 'wpautop');
        add_filter('the_content', 'shortcode_unautop');
        add_filter('the_content', 'prepend_attachment');
        // restore additional filters added by themes and plugins
        $wp_filter['the_content'][10] = array_merge($wp_filter['the_content'][10], $the_content);

        // provide an alternate implementation for the [raw] shortcode
        // ThemeForest's developer tried to achieve
        add_filter('the_content', array($this, 'run_raw_shortcode'), 8);
        add_filter('the_content', array($this, 'run_raw_shortcode_again'), 80);

        add_shortcode('awpcp-raw-token', array($this, 'raw_token_shortcode'));
    }

    private function _run_raw_shortcode($content, $token=false) {
        global $shortcode_tags;

        $backup = $shortcode_tags;
        remove_all_shortcodes();

        add_shortcode('raw', array($this, 'raw_shortcode'));
        // only [raw] shortcode is registered
        $content = do_shortcode($content);

        // remove all shortcodes again and process the output of raw_shortcode
        if ($token) {
            remove_all_shortcodes();
            add_shortcode('awpcp-raw-token', array($this, 'raw_token_shortcode'));
            // only [raw-token] shortcode is registered
            $content = do_shortcode($content);
        }

        // restore original shortcodes
        $shortcode_tags = $backup;

        return $content;
    }

    /**
     * Process the [raw] shortcode before wpautop and wptexturize are
     * executed.
     */
    public function run_raw_shortcode($content) {
        return $this->_run_raw_shortcode($content);
    }

    /**
     * Process [raw] shortcodes generated after normal shortcodes
     * have been processed.
     */
    public function run_raw_shortcode_again($content) {
        return $this->_run_raw_shortcode($content, true);
    }

    /**
     * Remove raw content to prevent formatting issues caused by
     * wpautop and wptexturize.
     *
     * A token id is placed where the raw content was, and the raw content
     * is inserted again after when regultar shortcode step is executed.
     */
    public function raw_shortcode($attrs, $raw) {
        $id = uniqid();
        $this->raw[$id] = $raw;
        return sprintf('[awpcp-raw-token id=%s]', $id);
    }

    /**
     * Replaces a raw token id with the content it represents.
     */
    public function raw_token_shortcode($attrs) {
        extract(shortcode_atts(array('id' => 0), $attrs));

        if (!$id || !isset($this->raw[$id])) return '';

        return $this->raw[$id];
    }
}
