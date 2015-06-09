<?php

function awpcp_form_fields_admin_page() {
    return new AWPCP_FormFieldsAdminPage(
        'awpcp-form-fields',
        awpcp_admin_page_title( __( 'Form Fields', 'AWPCP' ) ),
        awpcp_form_fields(),
        awpcp_form_fields_table_factory()
    );
}

class AWPCP_FormFieldsAdminPage extends AWPCP_AdminPageWithTable {

    private $form_fields;
    private $table_factory;

    public function __construct( $page, $title, $form_fields, $table_factory ) {
        parent::__construct( $page, $title, _x( 'Form Fields', 'sub menu title', 'AWPCP' ) );

        $this->form_fields = $form_fields;
        $this->table_factory = $table_factory;
    }

    public function get_table() {
        if ( ! isset( $this->table ) || is_null( $this->table ) ) {
            $this->table = $this->table_factory->create_table( $this );
        }

        return $this->table;
    }

    public function scripts() {
        wp_enqueue_script( 'awpcp-admin-form-fields' );
    }

    public function dispatch() {
        $form_fields = $this->form_fields->get_fields();

        $table = $this->get_table();
        $table->prepare( $form_fields, count( $form_fields ) );

        return $this->render_page();
    }

    public function render_page() {
        $params = array(
            'table' => $this->get_table(),
        );

        $template = AWPCP_DIR . '/templates/admin/form-fields-admin-page.tpl.php';

        echo $this->render( $template, $params );
    }
}
