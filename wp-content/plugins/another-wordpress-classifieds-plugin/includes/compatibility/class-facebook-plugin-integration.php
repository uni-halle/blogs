<?php

class AWPCP_FacebookPluginIntegration {

    private $metadata;

    public function should_generate_opengraph_tags( $should, $meta ) {
        if ( class_exists( 'Facebook_Loader' ) ) {
            $this->metadata = $meta->get_listing_metadata();
            add_action( 'fb_meta_tags', array( $this, 'meta_tags' ), 10, 2 );
            return false;
        }

        return $should;
    }

    public function meta_tags( $meta_tags, $post ) {
        return $this->metadata;
    }
}
