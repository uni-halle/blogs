<?php

function awpcp_file_uploader() {
    return new AWPCP_FileUploader(
        awpcp_file_types(),
        awpcp_mime_types(),
        awpcp_request(),
        awpcp()->settings
    );
}

class AWPCP_FileUploader {

    private $mime_types;
    private $config;
    private $request;
    private $settings;

    public function __construct( $config, $mime_types, $request, $settings ) {
        $this->config = $config;
        $this->mime_types = $mime_types;
        $this->request = $request;
        $this->settings = $settings;
    }

    public function get_uploaded_file() {
        return $this->try_to_upload_file( $this->get_posted_data() );
    }

    private function get_posted_data() {
        return array(
            'filename' => stripslashes( $this->get_uploaded_file_name() ),
            'chunk' => absint( $this->request->post( 'chunk' ) ),
            'chunks' => absint( $this->request->post( 'chunks' ) ),
        );
    }

    private function get_uploaded_file_name() {
        $filename = $this->request->post( 'name' );

        if ( empty( $filename ) && ! empty( $_FILES ) ) {
            $filename = $_FILES['file']['name'];
        } else if ( empty( $filename ) ) {
            throw new AWPCP_Exception( __( 'Unable to findout uploaded file name.', 'AWPCP' ) );
        }

        return $filename;
    }

    private function try_to_upload_file( $posted_data ) {
        if ( ! $this->is_filename_extension_allowed( $posted_data['filename'] ) ) {
            throw new AWPCP_Exception( __( 'File extension not allowed.', 'AWPCP' ) );
        }

        if ( $posted_data['chunks'] > 0 ) {
            $uploaded_file = $this->write_uploaded_chunk( $posted_data );
        } else {
            $uploaded_file = $this->write_uploaded_file( $posted_data );
        }

        return $uploaded_file;
    }

    private function is_filename_extension_allowed( $filename ) {
        $extensions = $this->config->get_allowed_file_extensions();
        return in_array( awpcp_get_file_extension( $filename ), $extensions );
    }

    private function write_uploaded_chunk( $posted_data ) {
        $file_path = $this->get_temporary_file_path( $posted_data['filename'] );
        $chunk_path = "$file_path.part{$posted_data['chunk']}";

        $this->write_uploaded_data_to_file( $chunk_path );

        if ( $posted_data['chunk'] == $posted_data['chunks'] - 1 ) {
            $this->write_uploaded_chunks_to_file( $posted_data['chunks'], $file_path );
            return $this->get_uploaded_file_info( $posted_data['filename'], $file_path, 'complete' );
        } else {
            return $this->get_uploaded_file_info( $posted_data['filename'], $chunk_path, 'incomplete' );
        }
    }

    private function get_temporary_file_path( $filename ) {
        $uploads_dir = $this->settings->get_runtime_option( 'awpcp-uploads-dir' );
        $tempory_dir_path = implode( DIRECTORY_SEPARATOR, array( $uploads_dir, 'tmp' ) );

        $pathinfo = awpcp_utf8_pathinfo( $filename );

        $new_name = wp_hash( $pathinfo['basename'] ) . '.' . $pathinfo['extension'];
        $unique_filename = wp_unique_filename( $tempory_dir_path, $new_name );

        return $tempory_dir_path . DIRECTORY_SEPARATOR . $unique_filename;
    }

    private function write_uploaded_data_to_file( $file_path ) {
        $base_dir = dirname( $file_path );

        if ( ! file_exists( $base_dir ) && ! mkdir( $base_dir, awpcp_directory_permissions(), true ) ) {
            throw new AWPCP_Exception( __( "Temporary directory doesn't exists and couldn't be created.", 'AWPCP' ) );
        }

        if ( ! empty( $_FILES ) && isset( $_FILES['file'] ) ) {
            if ( $_FILES['file']['error'] ) {
                list( $error_code, $error_message ) = awpcp_uploaded_file_error( $_FILES['file'] );
                throw new AWPCP_Exception( $error_message );
            }

            if ( ! is_uploaded_file( $_FILES['file']['tmp_name'] ) ) {
                throw new AWPCP_Exception( __( 'There was an error trying to move the uploaded file to a temporary location.', 'AWPCP' ) );
            }

            move_uploaded_file( $_FILES['file']['tmp_name'], $file_path );
        } else {
            if ( ! $input = fopen( 'php://input', 'rb' ) ) {
                throw new AWPCP_Exception( __( "There was an error trying to open PHP's input stream.", 'AWPCP' ) );
            }

            if ( ! $output = fopen( $file_path, 'wb' ) ) {
                throw new AWPCP_Exception( __( 'There was an error trying to open the output stream.', 'AWPCP' ) );
            }

            while ( $buffer = fread( $input, 4096 ) ) {
                fwrite( $output, $buffer );
            }

            fclose( $output );
            fclose( $input );
        }
    }

    private function write_uploaded_chunks_to_file( $chunks_count, $file_path ) {
        if ( ! $output = fopen( $file_path, 'wb' ) ) {
            throw new AWPCP_Exception( __( 'There was an error trying to open the output stream.', 'AWPCP' ) );
        }

        for ( $i = 0; $i < $chunks_count; $i = $i + 1 ) {
            $chunk_path = "$file_path.part$i";

            if ( ! file_exists( $chunk_path ) ) {
                throw new AWPCP_Exception( __( 'Missing chunk.', 'AWPCP' ) );
            }

            if ( ! $input = fopen( $chunk_path, 'rb' ) ) {
                throw new AWPCP_Exception( __( 'There was an error trying to open the input stream.', 'AWPCP' ) );
            }

            while ( $buffer = fread( $input, 4096 ) ) {
                fwrite( $output, $buffer );
            }

            fclose( $input );
            unlink( $chunk_path );
        }

        fclose( $output );
    }

    private function get_uploaded_file_info( $realname, $file_path, $progress='incomplete' ) {
        $mime_type = $this->mime_types->get_file_mime_type( $file_path );
        $pathinfo = awpcp_utf8_pathinfo( $file_path );

        return (object) array(
            'path' => $file_path,
            'realname' => strtolower( $realname ),
            'name' => $pathinfo['basename'],
            'dirname' => $pathinfo['dirname'],
            'filename' => $pathinfo['filename'],
            'extension' => $pathinfo['extension'],
            'mime_type' => $mime_type,
            'is_complete' => $progress === 'complete' ? true : false,
        );
    }

    private function write_uploaded_file( $posted_data ) {
        $file_path = $this->get_temporary_file_path( $posted_data['filename'] );
        $this->write_uploaded_data_to_file( $file_path );
        return $this->get_uploaded_file_info( $posted_data['filename'], $file_path, 'complete' );
    }
}
