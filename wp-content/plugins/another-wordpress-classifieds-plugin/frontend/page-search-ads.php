<?php

require_once(AWPCP_DIR . '/includes/helpers/page.php');


/**
 * @since  2.1.4
 */
class AWPCP_SearchAdsPage extends AWPCP_Page {

    public $messages = array();

    public function __construct($page='awpcp-search-ads', $title=null) {
        parent::__construct($page, is_null($title) ? __('Search Ads', 'AWPCP') : $title);
    }

    public function get_current_action($default='searchads') {
        return awpcp_request_param('a', $default);
    }

    public function url($params=array()) {
        $page_url = awpcp_get_page_url( 'search-ads-page-name', true );
        return add_query_arg( urlencode_deep( $params ), $page_url );
    }

    public function dispatch() {
        wp_enqueue_style('awpcp-jquery-ui');
        wp_enqueue_script('awpcp-page-search-listings');
        wp_enqueue_script('awpcp-extra-fields');

        $awpcp = awpcp();
        $awpcp->js->localize( 'page-search-ads', array(
            'keywordphrase' => __( 'You did not enter a keyword or phrase to search for. You must at the very least provide a keyword or phrase to search for.', 'AWPCP' )
        ) );

        return $this->_dispatch();
    }

    protected function _dispatch($default=null) {
        $action = $this->get_current_action();

        switch ($action) {
            case 'dosearch':
                return $this->do_search_step();
            case 'searchads':
            default:
                return $this->search_step();
        }
    }

    protected function get_posted_data() {
        $data = stripslashes_deep( array(
            'query' => awpcp_request_param('keywordphrase'),
            'category' => awpcp_request_param('searchcategory'),
            'name' => awpcp_request_param('searchname'),
            'min_price' => awpcp_parse_money( awpcp_request_param( 'searchpricemin' ) ),
            'max_price' => awpcp_parse_money( awpcp_request_param( 'searchpricemax' ) ),
            'regions' => awpcp_request_param('regions'),
        ) );

        $data = apply_filters( 'awpcp-get-posted-data', $data, 'search' );

        return $data;
    }

    protected function validate_posted_data($data, &$errors=array()) {
        $filtered = array_filter($data);

        if (empty($filtered)) {
            $errors[] = __("You did not enter a keyword or phrase to search for. You must at the very least provide a keyword or phrase to search for.", "AWPCP");
        }

        if (!empty($data['query']) && strlen($data['query']) < 3) {
            $errors['query'] = __("You have entered a keyword that is too short to search on. Search keywords must be at least 3 letters in length. Please try another term.", "AWPCP");
        }

        if (!empty($data['min_price']) && !is_numeric($data['min_price'])) {
            $errors['min_price'] = __("You have entered an invalid minimum price. Make sure your price contains numbers only. Please do not include currency symbols.", "AWPCP");
        }

        if (!empty($data['max_price']) && !is_numeric($data['max_price'])) {
            $errors['max_price'] = __("You have entered an invalid maximum price. Make sure your price contains numbers only. Please do not include currency symbols.", "AWPCP");
        }

        return empty($errors);
    }

    protected function search_step() {
        $this->messages[] = __("Use the form below to conduct a broad or narrow search. For a broader search enter fewer parameters. For a narrower search enter as many parameters as needed to limit your search to a specific criteria.", "AWPCP");
        return $this->search_form($this->get_posted_data());
    }

    protected function search_form($form, $errors=array()) {
        global $hasregionsmodule, $hasextrafieldsmodule;

        $ui['module-extra-fields'] = $hasextrafieldsmodule;
        $ui['posted-by-field'] = get_awpcp_option('displaypostedbyfield');
        $ui['price-field'] = get_awpcp_option('displaypricefield');
        $ui['allow-user-to-search-in-multiple-regions'] = get_awpcp_option('allow-user-to-search-in-multiple-regions');

        $messages = $this->messages;

        $url_params = wp_parse_args( parse_url( awpcp_current_url(), PHP_URL_QUERY ) );
        $hidden = awpcp_flatten_array( array_merge( $url_params, array( 'a' => 'dosearch' ) ) );

        $page = $this;
        $template = AWPCP_DIR . '/frontend/templates/page-search-ads.tpl.php';
        $params = compact('page', 'ui', 'form', 'hidden', 'messages', 'errors');

        return $this->render($template, $params);
    }

    protected function do_search_step() {
        $form = $this->get_posted_data();

        $errors = array();
        if (!$this->validate_posted_data($form, $errors)) {
            return $this->search_form($form, $errors);
        }

        $output = apply_filters( 'awpcp-search-listings-content-replacement', null, $form );

        if ( is_null( $output ) ) {
            return $this->search_listings( $form );
        } else {
            return $output;
        }
    }

    private function search_listings( $form ) {
        $query = array_merge( $form, array(
            'keyword' => $form['query'],
            'category_id' => $form['category'],
            'contact_name' => $form['name'],
            'min_price' => $form['min_price'],
            'max_price' => $form['max_price'],
            'regions' => $form['regions'],
            'disabled' => false,
            'limit' => absint( awpcp_request_param( 'results', get_awpcp_option( 'adresultsperpage', 10 ) ) ),
            'offset' => absint( awpcp_request_param( 'offset', 0 ) ),
            'orderby' => get_awpcp_option( 'search-results-order' ),
        ) );

        return awpcp_display_listings( $query, 'search', array(
            'show_intro_message' => true,
            'show_menu_items' => true,
            'show_category_selector' => false,
            'show_pagination' => true,

            'before_list' => $this->build_return_link(),
        ) );
    }

    public function build_return_link() {
        $params = array_merge(stripslashes_deep($_REQUEST), array('a' => 'searchads'));
        $href = add_query_arg(urlencode_deep($params), awpcp_current_url());

        $return_link = '<div class="awpcp-return-to-search-link awpcp-clearboth"><a href="<link-url>"><link-text></a></div>';
        $return_link = str_replace( '<link-url>', esc_url( $href ), $return_link );
        $return_link = str_replace( '<link-text>', __( 'Return to Search', 'AWPCP' ), $return_link );

        return $return_link;
    }
}
