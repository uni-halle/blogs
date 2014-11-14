<?php

require_once(AWPCP_DIR . '/includes/helpers/page.php');


class AWPCP_AdminPage extends AWPCP_Page {

    protected $template = 'admin/templates/admin-page.tpl.php';

    public $menu;

    public function __construct($page, $title, $menu) {
        parent::__construct($page, $title);
        $this->menu = $menu;
    }

    public function show_sidebar() {
        return true;
    }
}


class AWPCP_AdminPageWithTable extends AWPCP_AdminPage {

    protected $table = null;

    public $params = array();

    protected function params_blacklist() {
        // we don't need all this in our URLs, do we?
        return array(
            'action2', 'action', // action and bulk actions
            'selected', // selected rows for bulk actions
            '_wpnonce',
            '_wp_http_referer'
        );
    }

    public function get_current_action($default=null) {
        $blacklist = $this->params_blacklist();

        // return current bulk-action, if one was selected
        if (!$this->action)
            $this->action = $this->get_table()->current_action();

        if (!$this->action) {
            $this->action = awpcp_request_param('action', 'index');
        }

        if (!isset($this->params) || empty($this->params)) {
            wp_parse_str($_SERVER['QUERY_STRING'], $_params);
            $this->params = array_diff_key($_params, array_combine($blacklist, $blacklist));
        }

        return $this->action;
    }

    public function get_table() {
        return $this->table;
    }

    public function url($params=array(), $base=false) {
        $blacklist = $this->params_blacklist();
        $params = array_merge($this->params, $params);

        $url = remove_query_arg($blacklist, $base ? $base : awpcp_current_url());
        $url = add_query_arg($params, $url);

        return $url;
    }

    public function links($blueprints, $selected=null) {
        return awpcp_admin_page_links_builder()->build_links( $blueprints, $selected );
    }
}
