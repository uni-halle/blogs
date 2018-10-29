<?php
/**
 * @package AWPCP\Compatibility
 */

/**
 * @since 3.8.6
 */
function awpcp_complete_open_grap_plugin_integration() {
    return new AWPCP_CompleteOpenGraphPluginIntegration();
}

/**
 * Plugin integration for Complete Open Graph plugin.
 */
class AWPCP_CompleteOpenGraphPluginIntegration {

    /**
     * @var Meta
     */
    private $meta;

    /**
     * @since 3.8.6
     */
    public function load() {
        if ( is_admin() ) {
            return;
        }

        add_filter( 'awpcp-should-generate-opengraph-tags', array( $this, 'should_generate_open_graph_tags' ), 10, 2 );
    }

    /**
     * @since 3.8.6
     */
    public function should_generate_open_graph_tags( $should, $meta ) {
        if ( class_exists( '\CompleteOpenGraph\App' ) ) {
            $this->meta = $meta;

            add_filter( 'complete_open_graph_all_data', array( $this, 'filter_open_grap_data' ) );

            return false;
        }

        return $should;
    }

    /**
     * @since 3.8.6
     */
    public function filter_open_grap_data( $data ) {
        $metadata = $this->meta->get_listing_metadata();

        $groups = array(
            'og' => array(
                'url',
                'description',
                'title',
                'type',
                'image',
            ),
            'twitter' => array(
                'title',
                'image',
                'description',
            ),
        );

        foreach ( $groups as $group => $properties ) {
            foreach ( $properties as $property ) {
                $data_key     = "$group:$property";
                $metadata_key = "http://ogp.me/ns#$property";

                if ( ! isset( $data[ $data_key ]['value'] ) ) {
                    continue;
                }

                if ( ! isset( $metadata[ $metadata_key ] ) ) {
                    continue;
                }

                $data[ $data_key ]['value'] = $metadata[ $metadata_key ];
            }
        }

        return $data;
    }
}
