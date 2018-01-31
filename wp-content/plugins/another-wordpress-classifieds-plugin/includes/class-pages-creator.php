<?php

/**
 * @since 3.5.3
 */
function awpcp_pages_creator() {
    return new AWPCP_Pages_Creator(
        awpcp_missing_pages_finder(),
        awpcp()->settings
    );
}

/**
 * @since 3.5.3
 */
class AWPCP_Pages_Creator {

    private $missing_pages_finder;
    private $settings;

    public function __construct( $missing_pages_finder, $settings ) {
        $this->missing_pages_finder = $missing_pages_finder;
        $this->settings = $settings;
    }

    public function restore_missing_pages() {
        $shortcodes = awpcp_pages();

        foreach( $shortcodes as $refname => $properties ) {
            $page = $this->missing_pages_finder->find_page_with_shortcode( $properties[1] );

            if ( ! is_object( $page ) ) {
                continue;
            }

            $this->settings->update_option( $refname, $page->post_title );

            awpcp_update_plugin_page_id( $refname, $page->ID );
        }

        $missing_pages = $this->missing_pages_finder->find_broken_page_id_references();
        $pages_to_restore = array_merge( $missing_pages['not-found'], $missing_pages['not-referenced'] );
        $page_refs = awpcp_get_properties( $pages_to_restore, 'page' );

        // If we are restoring the main page, let's do it first!
        if ( ( $p = array_search( 'main-page-name', $page_refs ) ) !== FALSE ) {
            // put the main page as the first page to restore
            array_splice( $pages_to_restore, 0, 0, array( $pages_to_restore[ $p ] ) );
            array_splice( $pages_to_restore, $p + 1, 1 );
        }

        $restored_pages = array();

        foreach( $pages_to_restore as $page ) {
            $refname = $page->page;
            $name = get_awpcp_option($refname);
            if (strcmp($refname, 'main-page-name') == 0) {
                awpcp_create_pages($name, $subpages=false);
            } else {
                awpcp_create_subpage($refname, $name, $shortcodes[$refname][1]);
            }

            $restored_pages[] = $page;
        }

        update_option( 'awpcp-flush-rewrite-rules', true );

        return $restored_pages;
    }

}
