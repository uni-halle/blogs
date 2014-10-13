<?php

class AWPCP_CreditPlansTable extends WP_List_Table {

    public function __construct($page, $args=array()) {
        $args = array_merge(array('plural' => 'awpcp-credit-plans'), $args);
        parent::__construct($args);
        $this->page = $page;
    }

    private function parse_query() {
        $user = wp_get_current_user();
        $ipp = (int) get_user_meta($user->ID, 'credit-plans-items-per-page', true);
        $this->items_per_page = awpcp_request_param('items-per-page', $ipp === 0 ? 10 : $ipp);
        update_user_meta($user->ID, 'credit-plans-items-per-page', $this->items_per_page);

        $params = shortcode_atts(array(
            'orderby' => '',
            'order' => 'DESC',
            'paged' => 1,
        ), $_REQUEST);

        $params['order'] = strtoupper( $params['order'] ) == 'ASC' ? 'ASC' : 'DESC';

        switch($params['orderby']) {
            case 'price':
                $orderby = sprintf('price %1$s, name %1$s, id', $params['order']);
                break;

            case 'credits':
                $orderby = sprintf('credits %1$s, name %1$s, id', $params['order']);
                break;

            case 'name':
            default:
                $orderby = 'name';
                break;
        }

        return array(
            'orderby' => $orderby,
            'order' => $params['order'],
            'offset' => $this->items_per_page * ( absint( $params['paged'] ) - 1),
            'limit' => $this->items_per_page
        );
    }

    public function prepare_items() {
        $query = $this->parse_query();
        $this->total_items = AWPCP_CreditPlan::query(array_merge(array('fields' => 'count'), $query));
        $this->items = AWPCP_CreditPlan::query(array_merge(array('fields' => '*'), $query));

        $this->set_pagination_args(array(
            'total_items' => $this->total_items,
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
        $columns['description'] = __('Description', 'AWPCP');
        $columns['credits'] = __('Credits', 'AWPCP');
        $columns['price'] = __('Price', 'AWPCP');

        return $columns;
    }

    public function get_sortable_columns() {
        return array(
            'name' => array('name', true),
            'credits' => array('credits', true),
            'price' => array('price', true),
        );
    }

    private function get_row_actions($item) {
        $actions = $this->page->actions($item);
        return $this->page->links($actions);
    }

    public function column_default($item, $column_name) {
        return '...';
    }

    public function column_cb($item) {
        return '<input type="checkbox" value="' . $item->id . '" name="selected[]" />';
    }

    public function column_name($item) {
        return $item->name . $this->row_actions($this->get_row_actions($item), true);
    }

    public function column_description($item) {
        return $item->description;
    }

    public function column_credits($item) {
        return $item->get_formatted_credits();
    }

    public function column_price($item) {
        return $item->get_formatted_price();
    }

    public function single_row($item) {
        static $row_class = '';
        $row_class = ( $row_class == '' ? ' class="alternate"' : '' );

        echo '<tr id="credit-plan-' . $item->id . '" data-id="' . $item->id . '"' . $row_class . '>';
        echo $this->single_row_columns( $item );
        echo '</tr>';
    }
}
