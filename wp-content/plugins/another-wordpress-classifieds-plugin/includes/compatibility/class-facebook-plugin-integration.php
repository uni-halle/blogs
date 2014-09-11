<?php

class AWPCP_FacebookPluginIntegration {

    private $meta;

    public function should_generate_opengraph_tags( $should, $meta ) {
        $this->meta = $meta;

        if ( class_exists( 'Facebook_Loader' ) ) {
            add_action( 'fb_meta_tags', array( $this, 'meta_tags' ), 10, 2 );
            return false;
        }

        return $should;
    }

    public function meta_tags( $meta_tags, $post ) {
        return $this->meta->get_meta_tags();
    }
}
