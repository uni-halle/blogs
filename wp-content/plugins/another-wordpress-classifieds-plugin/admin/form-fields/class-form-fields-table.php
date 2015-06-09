<?php

class AWPCP_FormFieldsTable extends WP_List_Table {

    private $page;
    private $request;

    public function __construct( $page, $request ) {
        parent::__construct( array( 'plural' => 'awpcp-form-fields-table' ) );

        $this->page = $page;
        $this->request = $request;
    }

    // public function get_items_per_page( $option, $default = 10 ) {
    //     $user = $this->request->get_current_user();

    //     $items_per_page = (int) get_user_meta( $user->ID, $option, true );
    //     $items_per_page = awpcp_request_param( 'items-per-page', $items_per_page === 0 ? $default : $items_per_page );
    //     update_user_meta( $user->ID, $option, $items_per_page );

    //     return $items_per_page;
    // }

    public function prepare( $items, $total_items ) {
        $this->items = $items;

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page' => $total_items,
        ) );

        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
    }

    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'slug' => _x( 'Slug', 'form field slug', 'AWPCP' ),
            'name' => _x( 'Name', 'form field name', 'AWPCP' ),
        );

        return $columns;
    }

    public function column_cb($item) {
        return '<input type="checkbox" value="' . $item->get_slug() . '" name="selected[]" />';
    }

    public function column_slug( $item ) {
        return $item->get_slug();
    }

    public function column_name( $item ) {
        return $item->get_name() . '<div class="awpcp-sortable-handle"><div class="spinner awpcp-spinner awpcp-form-fields-table-spinner"></div></div>';
    }

    public function single_row($item) {
        static $row_class = '';

        $row_class = ( $row_class == '' ? ' class="alternate"' : '' );

        // the 'field-' part in the id attribute is important. The jQuery UI Sortable plugin relies on that
        // to build a serialized string with the current order of fields.
        echo '<tr id="field-' . $item->get_slug() . '" data-id="' . $item->get_slug() . '"' . $row_class . '>';
        echo $this->single_row_columns( $item );
        echo '</tr>';
    }
}
