<?php

abstract class AWPCP_ListingAction {

    public function is_enabled_for_listing( $listing ) {
        return true;
    }

    public abstract function get_slug();
    public abstract function get_name();
    public abstract function get_description();

    public function get_endpoint( $listing, $config ) {
        return $config['current-url'];
    }

    public function filter_params( $params ) {
        return array_merge( $params, array( 'step' => $this->get_slug() ) );
    }

    public function get_submit_button_label() {
        return $this->get_name();
    }

    public function render( $listing, $config ) {
        ob_start();
        include( $this->get_template() );
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    protected function get_template() {
        return AWPCP_DIR . '/templates/frontend/listing-action.tpl.php';
    }
}
