<?php

class AWPCP_Classifieds_Menu_Component {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function render() {
        $template = AWPCP_DIR . '/templates/components/classifieds-menu.tpl.php';
        $params = array( 'buttons' => $this->get_menu_buttons() );

        return awpcp_render_template( $template, $params );
    }

    private function get_menu_buttons() {
        return awpcp_get_menu_items( array(
            'show-create-listing-button' => $this->settings->get_option( 'show-menu-item-place-ad', true ),
            'show-edit-listing-button' => $this->settings->get_option( 'show-menu-item-edit-ad', true ),
            'show-browse-listings-button' => $this->settings->get_option( 'show-menu-item-browse-ads', true ),
            'show-search-listings-button' => $this->settings->get_option( 'show-menu-item-search-ads', false ),
        ) );
    }
}

