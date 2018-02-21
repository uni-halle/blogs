<?php

/**
 * @since 3.6
 */
function awpcp_plugin_rewrite_rules() {
    return new AWPCP_Plugin_Rewrite_Rules( awpcp_rewrite_rules_helper() );
}

/**
 * @since 3.6
 */
class AWPCP_Plugin_Rewrite_Rules {

    private $rewrite_rules_helper;

    public function __construct( $rewrite_rules_helper ) {
        $this->rewrite_rules_helper = $rewrite_rules_helper;
    }

    public function add_rewrite_rules( $rules ) {
        $this->add_api_rewrite_rules();
        $this->add_plugin_pages_rewrite_rules();
        $this->add_legacy_plugin_pages_rewrite_rules();

        return $rules;
    }

    private function get_pages_uris( $pages ) {
        $uris = array();

        foreach ( $pages as $refname ) {
            $page_id = awpcp_get_page_id_by_ref( $refname );

            if ( ! $page_id ) {
                continue;
            }

            $page = get_post( $page_id );

            if ( is_null( $page ) ) {
                continue;
            }

            $uris[ $refname ] = $this->rewrite_rules_helper->generate_page_uri_variants( get_page_uri( $page->ID ) );
        }

        return $uris;
    }

    private function add_api_rewrite_rules() {
        // Payments API rewrite rules
        $this->rewrite_rules_helper->add_page_rewrite_rule(
            'awpcpx/payments/return/([a-zA-Z0-9]+)',
            'index.php?awpcpx=1&awpcp-module=payments&awpcp-action=return&awpcp-txn=$matches[1]',
            'top'
        );
        $this->rewrite_rules_helper->add_page_rewrite_rule(
            'awpcpx/payments/notify/([a-zA-Z0-9]+)',
            'index.php?awpcpx=1&awpcp-module=payments&awpcp-action=notify&awpcp-txn=$matches[1]',
            'top'
        );
        $this->rewrite_rules_helper->add_page_rewrite_rule(
            'awpcpx/payments/cancel/([a-zA-Z0-9]+)',
            'index.php?awpcpx=1&awpcp-module=payments&awpcp-action=cancel&awpcp-txn=$matches[1]',
            'top'
        );

        // Ad Email Verification rewrite rules
        $this->rewrite_rules_helper->add_page_rewrite_rule(
            'awpcpx/listings/verify/([0-9]+)/([a-zA-Z0-9]+)',
            'index.php?awpcpx=1&awpcp-module=listings&awpcp-action=verify&awpcp-ad=$matches[1]&awpcp-hash=$matches[2]',
            'top'
        );
    }

    private function add_plugin_pages_rewrite_rules() {
        $pages_rules = $this->get_pages_rewrite_rules_definitions();
        $pages_uris = $this->get_pages_uris( array_keys( $pages_rules ) );

        foreach ( $pages_rules as $page_ref => $rules ) {
            if ( ! isset( $pages_uris[ $page_ref ] ) ) {
                continue;
            }

            foreach ( $rules as $rule ) {
                foreach ( $pages_uris[ $page_ref ] as $page_uri ) {
                    $regex = str_replace( '<page-uri>', $page_uri, $rule['regex'] );
                    $this->rewrite_rules_helper->add_page_rewrite_rule( $regex, $rule['redirect'], $rule['position'] );
                }
            }
        }
    }

    private function get_pages_rewrite_rules_definitions() {
        $rewrite_rules = array(
            'show-ads-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/(\d+)(?:.*)',
                    'redirect' => 'index.php?pagename=$matches[1]&id=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'reply-to-ad-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/(\d+)(?:.*)',
                    'redirect' => 'index.php?pagename=$matches[1]&id=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'edit-ad-page-name' => array(
                array(
                    'regex' => '(<page-uri>)(?:/([0-9]+))?',
                    'redirect' => 'index.php?pagename=$matches[1]&id=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'browse-ads-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/(\d+)(?:.*)',
                    'redirect' => 'index.php?pagename=$matches[1]&cid=$matches[2]',
                    'position' => 'top'
                ),
            ),
            // TODO: Unused. Remove rewrite rule.
            'payment-thankyou-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/([a-zA-Z0-9]+)',
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-txn=$matches[2]',
                    'position' => 'top'
                ),
            ),
            // TODO: Unused. Remove rewrite rule.
            'payment-cancel-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/([a-zA-Z0-9]+)',
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-txn=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'main-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/(setregion)/(.+?)/(.+?)',
                    'redirect' => 'index.php?pagename=$matches[1]&regionid=$matches[3]',
                    'position' => 'top'
                ),
                array(
                    'regex' => '(<page-uri>)/(classifiedsrss)/(\d+)',
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-action=rss&cid=$matches[3]',
                    'position' => 'top'
                ),
                array(
                    'regex' => '(<page-uri>)/(classifiedsrss)',
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-action=rss',
                    'position' => 'top'
                ),
            ),
        );

        $view_categories = sanitize_title( get_awpcp_option( 'view-categories-page-name' ) );

        if ( $view_categories ) {
            array_unshift( $rewrite_rules['main-page-name'], array(
                'regex' => '(<page-uri>)/('.$view_categories.')',
                'redirect' => 'index.php?pagename=$matches[1]&layout=2',
                'position' => 'top'
            ) );
        }

        return $rewrite_rules;
    }

    private function add_legacy_plugin_pages_rewrite_rules() {
        $browse_categories_page_info = get_option( 'awpcp-browse-categories-page-information', array() );

        if ( empty( $browse_categories_page_info['page_uri'] ) ) {
            return;
        }

        $page_uris = $this->rewrite_rules_helper->generate_page_uri_variants( $browse_categories_page_info['page_uri'] );
        $browse_listings_page_id = awpcp_get_page_id_by_ref( 'browse-ads-page-name' );
        $base_regex = '<page-uri>(?:$|/(\d+)?)';
        $base_redirect = 'index.php?page_id=<browse-listings-page-id>&cid=$matches[1]&awpcp-custom=redirect-browse-listings';

        foreach ( $page_uris as $page_uri ) {
            $regex = str_replace( '<page-uri>', $page_uri, $base_regex );
            $redirect = str_replace( '<browse-listings-page-id>', $browse_listings_page_id, $base_redirect );

            $this->rewrite_rules_helper->add_page_rewrite_rule( $regex, $redirect, 'top' );
        }
    }
}
