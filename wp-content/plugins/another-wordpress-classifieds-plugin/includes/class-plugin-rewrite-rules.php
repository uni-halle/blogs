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

        return $rules;
    }

    private function get_pages_uris( $pages ) {
        $uris = array();

        foreach ( $pages as $refname ) {
            if ( $id = awpcp_get_page_id_by_ref( $refname ) ) {
                if ( $page = get_page( $id ) ) {
                    $regular_page_uri = get_page_uri( $page->ID );

                    $uppercase_page_uri = preg_replace_callback(
                        '/%[0-9a-zA-Z]{2}/',
                        create_function( '$x', 'return strtoupper( $x[0] );' ),
                        $regular_page_uri
                    );

                    if ( strcmp( $regular_page_uri, $uppercase_page_uri ) !== 0 ) {
                        $uris[ $refname ] = array( $regular_page_uri, $uppercase_page_uri );
                    } else {
                        $uris[ $refname ] = array( $regular_page_uri );
                    }
                }
            }
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
        $view_categories = sanitize_title(get_awpcp_option('view-categories-page-name'));

        return array(
            'show-ads-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/(\d+)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&id=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'reply-to-ad-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/(.+?)/(.+?)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&id=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'edit-ad-page-name' => array(
                array(
                    'regex' => '(<page-uri>)(?:/([0-9]+))?/?$' ,
                    'redirect' => 'index.php?pagename=$matches[1]&id=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'browse-categories-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/(\d+)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&cid=$matches[2]&a=browsecat',
                    'position' => 'top'
                ),
            ),
            'payment-thankyou-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/([a-zA-Z0-9]+)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-txn=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'payment-cancel-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/([a-zA-Z0-9]+)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-txn=$matches[2]',
                    'position' => 'top'
                ),
            ),
            'main-page-name' => array(
                array(
                    'regex' => '(<page-uri>)/('.$view_categories.')($|[/?])' ,
                    'redirect' => 'index.php?pagename=$matches[1]&layout=2&cid='.$view_categories,
                    'position' => 'top'
                ),
                array(
                    'regex' => '(<page-uri>)/(setregion)/(.+?)/(.+?)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&regionid=$matches[3]&a=setregion',
                    'position' => 'top'
                ),
                array(
                    'regex' => '(<page-uri>)/(classifiedsrss)/(\d+)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-action=rss&cid=$matches[3]',
                    'position' => 'top'
                ),
                array(
                    'regex' => '(<page-uri>)/(classifiedsrss)' ,
                    'redirect' => 'index.php?pagename=$matches[1]&awpcp-action=rss',
                    'position' => 'top'
                ),
            ),
        );
    }
}
