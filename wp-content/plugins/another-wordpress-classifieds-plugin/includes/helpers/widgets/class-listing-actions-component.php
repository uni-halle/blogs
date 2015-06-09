<?php

function awpcp_listing_actions_component() {
    return new AWPCP_ListingActionsComponent();
}

/**
 * Component shown on tpp of the listing details form while editing the listing
 * in a frontend screen.
 *
 * The main plugin uses the component to show the Delete Ad button, and modules
 * can enter additional actions as necessary.
 *
 * @since 3.4
 */
class AWPCP_ListingActionsComponent {

    public function render( $listing, $config = array() ) {
        $config = wp_parse_args( $config, array(
            'current-url' => awpcp_current_url(),
            'hidden-params' => array(),
        ) );

        $actions = apply_filters( 'awpcp-listing-actions', array(), $listing );

        ob_start();
        include( AWPCP_DIR . '/templates/components/listings-actions.tpl.php' );
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}
