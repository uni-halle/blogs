<?php

class AWPCP_FeesTable extends WP_List_Table {

    private $page;
    private $items_per_page;
    private $total_items;

    public function __construct($page, $args=array()) {
        parent::__construct(wp_parse_args($args, array('plural' => 'awpcp-fees')));
        $this->page = $page;
    }

    private function parse_query() {
        global $wpdb;

        $user = wp_get_current_user();
        $ipp = (int) get_user_meta($user->ID, 'fees-items-per-page', true);
        $this->items_per_page = awpcp_request_param('items-per-page', $ipp === 0 ? 10 : $ipp);
        update_user_meta($user->ID, 'fees-items-per-page', $this->items_per_page);

        $params = shortcode_atts(array(
            'orderby' => '',
            'order' => 'desc',
            'paged' => 1,
        ), $_REQUEST);

        $params['order'] = strcasecmp($params['order'], 'DESC') === 0 ? 'DESC' : 'ASC';
        $params['pages'] = (int) $params['paged'];

        switch($params['orderby']) {
            case 'duration':
                $orderby = sprintf('rec_period %1$s, adterm_name', $params['order']);
                break;

            case 'interval':
                $orderby = sprintf('rec_increment %1$s, adterm_name', $params['order']);
                break;

            case 'images':
                $orderby = sprintf('imagesallowed %1$s, adterm_name', $params['order']);
                break;

            case 'title-characters':
                $orderby = sprintf( 'title_characters %1$s, adterm_name', $params['order'] );
                break;

            case 'characters':
                $orderby = sprintf('characters_allowed %1$s, adterm_name', $params['order']);
                break;

            case 'price':
                $orderby = sprintf('amount %1$s, adterm_name', $params['order']);
                break;

            case 'credits':
                $orderby = sprintf('credits %1$s, adterm_name', $params['order']);
                break;

            case 'categories':
                $orderby = sprintf('categories %1$s, adterm_name', $params['order']);
                break;

            case 'featured':
                $orderby = sprintf('is_featured_ad_pricing %1$s, adterm_name', $params['order']);
                break;

            case 'private':
                $orderby = sprintf( 'private %1$s, adterm_name', $params['order'] );
                break;

            case 'name':
            default:
                $orderby = 'adterm_name';
                break;
        }

        return array(
            'orderby' => $orderby,
            'order' => $params['order'],
            'offset' => $this->items_per_page * ($params['paged'] - 1),
            'limit' => $this->items_per_page
        );
    }

    public function prepare_items() {
        $query = $this->parse_query();

        $total_items = AWPCP_Fee::query(array_merge(array('fields' => 'count'), $query));
        $this->items = AWPCP_Fee::query(array_merge(array('fields' => '*'), $query));

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $this->items_per_page
        ));

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
    }

    public function has_items() {
        return count($this->items) > 0;
    }

    public function get_columns() {
        $columns = array();

        $columns['cb'] = '<input type="checkbox" />';
        $columns['name'] = __('Name', 'AWPCP');
        $columns['duration'] = __('Duration', 'AWPCP');
        $columns['interval'] = __('Units', 'AWPCP');
        $columns['images'] = __('Images Allowed', 'AWPCP');
        $columns['title_characters'] = __( 'Characters in Title', 'AWPCP' );
        $columns['characters'] = __('Characters in Description', 'AWPCP');
        $columns['price'] = __('Price', 'AWPCP');
        $columns['credits'] = __('Credits', 'AWPCP');

        if (function_exists('awpcp_price_cats'))
            $columns['categories'] = __('Categories', 'AWPCP');

        if (function_exists('awpcp_featured_ads'))
            $columns['featured'] = __('Featured Ads', 'AWPCP');

        $columns['private'] = __( 'Private', 'AWPCP' );

        return $columns;
    }

    public function get_sortable_columns() {
        $columns = array(
            'name' => array('name', true),
            'duration' => array('duration', true),
            'interval' => array('interval', true),
            'images' => array('images', true),
            'title_characters' => array('title-characters', true),
            'characters' => array('characters', true),
            'price' => array('price', true),
            'credits' => array('credits', true),
            'private' => array( 'private', true ),
        );

        if (function_exists('awpcp_price_cats'))
            $columns['categories'] = array('categories', true);

        if (function_exists('awpcp_featured_ads'))
            $columns['featured'] = array('featured', true);

        return $columns;
    }

    private function get_row_actions($item) {
        $actions = $this->page->actions($item);
        return $this->page->links($actions);
    }

    public function column_cb($item) {
        return '<input type="checkbox" value="' . $item->id . '" name="selected[]" />';
    }

    public function column_name($item) {
        return $item->get_name() . $this->row_actions($this->get_row_actions($item), true);
    }

    public function column_duration($item) {
        return $item->duration_amount;
    }

    public function column_interval($item) {
        return $item->get_duration_interval();
    }

    public function column_images($item) {
        return $item->images;
    }

    public function column_characters($item) {
        return $item->get_characters_allowed();
    }

    public function column_title_characters($item) {
        return $item->get_characters_allowed_in_title();
    }

    public function column_price($item) {
        return number_format($item->price, 2);
    }

    public function column_credits($item) {
        return number_format($item->credits, 0);
    }

    public function column_categories($item) {
        if ( !empty( $item->categories ) ) {
            $categories = AWPCP_Category::find( array( 'id' => $item->categories ) );
        } else {
            $categories = array();
        }

        return awpcp_get_comma_separated_categories_list( $categories );
    }

    public function column_featured($item) {
        return $item->featured ? __('Yes', 'AWPCP') : __('No', 'AWPCP');
    }

    public function column_private($item) {
        return $item->private ? __( 'Yes', 'AWPCP' ) : __( 'No', 'AWPCP' );
    }

    public function single_row($item) {
        static $row_class = '';
        $row_class = ( $row_class == '' ? ' class="alternate"' : '' );

        echo '<tr id="fee-' . $item->id . '" data-id="' . $item->id . '"' . $row_class . '>';
        echo $this->single_row_columns( $item );
        echo '</tr>';
    }
}
