<?php

function awpcp_file_manager_component() {
    return new AWPCP_FileManagerComponent( awpcp()->js );
}

class AWPCP_FileManagerComponent {

    private $javascript;

    private $options = array();

    public function __construct( $javascript ) {
        $this->javascript = $javascript;
    }

    public function configure( $options ) {
        $this->options = wp_parse_args( $options, array(
            'images_allowed' => 0,
            'images_left' => 0,
        ) );
    }

    public function render( $listing, $files = array() ) {
        if ( empty( $this->options ) ) {
            throw new Exception( __( 'File Manager component is not properly configured', 'AWPCP' ) );
        }

        $data = $this->prepare_component_data( $listing, $files );

        $this->javascript->set( 'file-manager-data', $data );

        ob_start();
        include( AWPCP_DIR . '/templates/components/file-manager.tpl.php' );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    private function prepare_component_data( $listing, $files ) {
        $this->try_to_find_primary_image_id( $files );

        return array(
            'files' => $this->prepare_files( $files ),
            'options' => $this->translate_options( $this->options ),
            'nonce' => $this->generate_nonce_value( $listing ),
        );
    }

    private function try_to_find_primary_image_id( $files ) {
        foreach( $files as $file ) {
            if ( $file->is_primary() ) {
                $this->options['primary_image_id'] = $file->id;
                break;
            }
        }
    }

    private function prepare_files( $files ) {
        $data = array();

        foreach ( $files as $file ) {
            $object = new stdClass();

            $object->id = $file->id;
            $object->name = $file->name;
            $object->listing_id = $file->ad_id;
            $object->enabled = $file->enabled;
            $object->isImage = $file->is_image();
            $object->isPrimaryImage = $file->is_primary();
            $object->thumbnailUrl = $file->get_url( 'thumbnail' );
            $object->iconUrl = $file->get_icon_url();
            $object->url = $file->get_url();
            $object->status = $file->status;

            array_push( $data, $object );

            if ( ! isset( $this->options['primary_image_id'] ) && $object->isPrimaryImage ) {
                $this->options['primary_image_id'] = $object->id;
            }
        }

        return $data;
    }

    private function translate_options( $source ) {
        $options = array();

        foreach ( $source as $key => $value ) {
            $new_key = str_replace( '_', ' # ', $key );
            $new_key = ucwords( $new_key );
            $new_key = str_replace( ' # ', '', $new_key );
            $new_key = strtolower( substr( $new_key, 0, 1 ) ) . substr( $new_key, 1 );

            $options[ $new_key ] = $value;
        }

        return $options;
    }

    private function generate_nonce_value( $listing ) {
        return wp_create_nonce( 'manage-listing-files-' . $listing->ad_id );
    }
}
