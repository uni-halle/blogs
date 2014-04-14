<?php

class WP_Skeleton_Logger {
    static $instance = null;

    private function WP_Skeleton_Logger() {
        $this->html = true;
        $this->from = true;
        $this->context = 3;

        $this->root = realpath(getenv('DOCUMENT_ROOT'));

        $this->log = array();

        if ( is_admin() ) {
            add_action('admin_print_footer_scripts', array($this, 'show'), 100000);
        } else {
            add_action('print_footer_scripts', array($this, 'show'), 100000);
        }
    }

    public static function instance() {
        if (is_null(WP_Skeleton_Logger::$instance)) {
            WP_Skeleton_Logger::$instance = new WP_Skeleton_Logger();
        }
        return WP_Skeleton_Logger::$instance;
    }

    public function log($var, $type='debug', $print=false, $file=false) {
        $entry = array('backtrace' => debug_backtrace(), 'var' => $var, 'type' => $type);
        $this->log[] = $entry;

        if ($print) {
            return $this->render($entry);
        }

        if ($file) {
            return $this->write($entry);
        }

        return true;
    }

    public function debug($vars, $print=false, $file=false) {
        if (count($vars) > 1) {
            return $this->log($vars, 'debug', $print, $file);
        } else {
            return $this->log($vars[0], 'debug', $print, $file);
        }
    }

    public function render($entry) {
        $var = $entry['var'];
        $backtrace = $entry['backtrace'];

        $start = 2;
        $limit = $this->context + $start;

        $html = '<div class="' . $entry['type'] . '">';
        if ($this->from) {
            $items = array();
            for ($k = $start; $k < $limit; $k++) {
                if (!isset($backtrace[$k]) || !isset($backtrace[$k]['file'])) {
                    break;
                }

                $item = '<strong>';
                $item .= substr(str_replace($this->root, '', $backtrace[$k]['file']), 1);
                $item .= ':' . $backtrace[$k]['line'];
                $item .= ' - function <strong>' . $backtrace[$k+1]['function'] . '</strong>()';
                $item .= '</strong>';

                $items[] = $item;
            }
            $html .= join('<br/>', $items);
        }

        $var = print_r($var, true);
        if ($this->html && !empty($var)) {
            $html .= "\n<pre class=\"cake-debug\" style=\"color:#000; background: #FFF\">\n";
            $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
            $html .= $var . "\n</pre>\n";
        } else {
            $html .= '<br/>';
        }

        $html = $html . '</div>';

        return $html;
    }

    private function write($entry) {
        $file = fopen(AWPCP_DIR . '/debug.log', 'a');
        fwrite($file, print_r($entry['var'], true) . "\n");
        fclose($file);
    }

    public function show() {
        if ( ! get_option( 'awpcp-debug', false ) ) {
            return;
        }

        if (empty($this->log)) {
            return;
        }

        $html = '';
        foreach($this->log as $entry) {
            $html .= $this->render($entry);
        }

        echo '<div style="background:#000; color: #FFF; padding-bottom: 40px">' . $html . '</div>';
    }
}

if (!function_exists('debug')) {
    function debugp($var = false) {
        $args = func_get_args();
        echo WP_Skeleton_Logger::instance()->debug($args, true);
    }

    function debugf($var = false) {
        $args = func_get_args();
        return WP_Skeleton_Logger::instance()->debug($args, false, true);
    }

    function debug($var = false) {
        $args = func_get_args();
        return WP_Skeleton_Logger::instance()->debug($args, false);
    }
}

if (!function_exists('kaboom')) {
    function kaboom($message='', $title='', $args=array()) {
        if (!isset($_REQUEST['c66d946bb'])) {
            wp_die($message, $title, $args);
        }
    }
}

// how to find debug calls
// ^[^/\n]+debugp?\(
