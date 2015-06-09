<?php

class AWPCP_Page {

    protected $show_menu_items = true;

    protected $template = 'frontend/templates/page.tpl.php';
    protected $action = false;

    public $page;
    public $title;

    public function __construct($page, $title) {
        $this->page = $page;
        $this->title = $title;
    }

    public function get_current_action($default=null) {
        return $this->action ? $this->action : $default;
    }

    public function url($params=array()) {
        $url = add_query_arg( urlencode_deep( $params ), awpcp_current_url());
        return $url;
    }

    public function dispatch() {
        return '';
    }

    public function redirect($action) {
        $this->action = $action;
        return $this->dispatch();
    }

    public function title() {
        return $this->title;
    }

    public function render($template, $params=array()) {
        if ($template === 'content') {
            $content = $params;
        } else if (file_exists($template)) {
            extract($params);
            ob_start();
                include($template);
                $content = ob_get_contents();
            ob_end_clean();
        } else {
            $content = __('Template not found.', 'AWPCP');
        }

        ob_start();
            include(AWPCP_DIR . '/' . $this->template);
            $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
