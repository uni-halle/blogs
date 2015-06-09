<?php

function awpcp_add_meta_tags_plugin_integration() {
    return new AWPCP_AddMetaTagsPluginIntegration( awpcp_meta_tags_generator() );
}

class AWPCP_AddMetaTagsPluginIntegration {

    private $meta_tags_generator;

    public function __construct( $meta_tags_generator ) {
        $this->meta_tags_generator = $meta_tags_generator;
    }

    public function should_generate_opengraph_tags( $should, AWPCP_Meta $meta ) {
        if ( ! function_exists( 'amt_get_metadata_head' ) ) {
            return $should;
        }

        $options = get_option( 'add_meta_tags_opts' );

        if ( $options['auto_opengraph'] == '1' ) {
            $this->metadata = $meta->get_listing_metadata();
            add_filter( 'amt_opengraph_metadata_head', array( $this, 'overwrite_opengraph_metadata' ) );
            return false;
        } else if ( ! empty( $options['site_wide_meta'] ) ) {
            $this->metadata = $meta->get_listing_metadata();
            add_filter( 'amt_basic_metadata_head', array( $this, 'remove_opengraph_metadata' ) );
            return $should;
        }

        return $should;
    }

    public function overwrite_opengraph_metadata( $meta_tags ) {
        $opengraph_meta_tags = $this->meta_tags_generator->generate_opengraph_meta_tags( $this->metadata );
        $meta_tags_replaced = array();

        foreach ( $meta_tags as $index => $tag ) {
            if ( ! preg_match( '/property="([^"]+)"/', $tag, $matches) ) {
                continue;
            }

            if ( ! isset( $opengraph_meta_tags[ $matches[1] ] ) ) {
                continue;
            }

            $meta_tags[ $index ] = $opengraph_meta_tags[ $matches[1] ];
            $meta_tags_replaced[] = $matches[1];
        }

        $meta_tags_not_included = array_diff( array_keys( $opengraph_meta_tags ), $meta_tags_replaced );

        foreach ( $meta_tags_not_included as $property ) {
            $meta_tags[] = $opengraph_meta_tags[ $property ];
        }

        return $meta_tags;
    }

    public function remove_opengraph_metadata( $meta_tags ) {
        $opengraph_meta_tags = $this->meta_tags_generator->generate_meta_tags( $this->metadata );

        $regex_partials = array();
        foreach ( array_keys( $opengraph_meta_tags ) as $property ) {
            $regex_partials[] = '(?:<[^>]+=(?:"|\')' . preg_quote( $property ) . '(?:"|\')[^>]+>)';
        }

        $pattern = '/' . implode( '|', $regex_partials ) . '/';

        foreach ( $meta_tags as $index => $tag ) {
            $meta_tags[ $index ] = preg_replace( $pattern, '', $tag );
        }

        return $meta_tags;
    }
}
