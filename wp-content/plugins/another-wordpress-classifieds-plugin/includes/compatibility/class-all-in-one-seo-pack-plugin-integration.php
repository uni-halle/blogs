<?php

/**
 * @since 3.2.1
 */
class AWPCP_AllInOneSEOPackPluginIntegration {

    private $metadata;

    public function should_generate_opengraph_tags( $should, $meta ) {
        if ( class_exists( 'All_in_One_SEO_Pack_Opengraph' ) ) {
            $this->metadata = $meta->get_listing_metadata();
            add_filter( 'aiosp_opengraph_meta', array( $this, 'meta_tag_value' ), 10, 3 );
            return false;
        }

        return $should;
    }

    public function meta_tag_value( $value, $group, $name ) {
        if ( $group === 'facebook' ) {
            $value = $this->facebook_meta_tag_value( $name, $value );
        } else if ( $group === 'twitter') {
            $value = $this->twitter_meta_tag_value( $name, $value );
        }

        return $value;
    }

    private function facebook_meta_tag_value( $name, $value ) {
        switch ( $name ) {
            case 'title':
                $value = $this->metadata['http://ogp.me/ns#title'];
                break;
            case 'description':
                $value = $this->metadata['http://ogp.me/ns#description'];
                break;
            case 'type':
                $value = $this->metadata['http://ogp.me/ns#type'];
                break;
            case 'url':
                $value = $this->metadata['http://ogp.me/ns#url'];
                break;
            case 'thumbnail':
                $value = $this->metadata['http://ogp.me/ns#image'];
                break;
        }

        return $value;
    }

    private function twitter_meta_tag_value( $name, $value ) {
        switch ( $name ) {
            case 'description':
                $value = $this->metadata['http://ogp.me/ns#description'];
                break;
        }

        return $value;
    }

    public function should_generate_rel_canonical( $should, $meta ) {
        if ( class_exists( 'All_in_One_SEO_Pack' ) ) {
            add_filter( 'aioseop_canonical_url', array( $this, 'canonical_url' ) );
            return false;
        }

        return $should;
    }

    /**
     * TODO: move to a parent class for all SEO plugin integrations.
     */
    public function canonical_url( $url ) {
        $awpcp_canonical_url = awpcp_rel_canonical_url();

        if ( $awpcp_canonical_url ) {
            return $awpcp_canonical_url;
        }

        return $url;
    }
}
