<?php

function awpcp_listing_url_settings() {
    return new AWPCP_ListingsURLSettings();
}

class AWPCP_ListingsURLSettings {

    public function register_settings( $settings ) {
        $key = $settings->add_section( 'listings-settings', 'Listing URL', 'listing-url', 50, array( $this, 'render_section_header' ) );

        $settings->add_setting(
            $key,
            'include-title-in-listing-url',
            __( 'Include the title in the listing URL', 'AWPCP' ),
            'checkbox',
            1,
            __( 'Include the title in the URL that points to the page of an individual listing.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'include-category-in-listing-url',
            __( 'Include the name of the category in the listing URL', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'showcategoryinpagetitle' ),
            __( 'Include the name of the category in the URL that points to the page of an individual listing.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'include-country-in-listing-url',
            __( 'Include the name of the country in the listing URL', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'showcountryinpagetitle' ),
            __( 'Include the name of the country in the URL that points to the page of an individual listing.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'include-state-in-listing-url',
            __( 'Include the name of the state in the listing URL', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'showstateinpagetitle' ),
            __( 'Include the name of the state in the URL that points to the page of an individual listing.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'include-city-in-listing-url',
            __( 'Include the name of the city in the listing URL', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'showcityinpagetitle' ),
            __( 'Include the name of the city in the URL that points to the page of an individual listing.', 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'include-county-in-listing-url',
            __( 'Include the name of the county in the listing URL', 'AWPCP' ),
            'checkbox',
            $settings->get_option( 'showcountyvillageinpagetitle' ),
            __( 'Include the name of the county in the URL that points to the page of an individual listing.', 'AWPCP' )
        );
    }

    public function render_section_header() {
        $introduction = _x( 'These settings affect the URL path shown for the listing. You can include or remove certain elements for SEO purposes', 'listing url settings section', 'AWPCP' );

        $example_path = '<code>/awpcp/state/city/listing-title</code>';
        $example_text = _x( 'Example: <example-path>.', 'listing url settings section', 'AWPCP' );
        $example_text = str_replace( '<example-path>', $example_path, $example_text );

        echo '<p>' . $introduction . '<br>' . $example_text . '</p>';
    }
}
