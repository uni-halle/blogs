<?php

function awpcp_filesystem() {
    return new AWPCP_Filesystem( awpcp()->settings );
}

class AWPCP_Filesystem {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function get_uploads_dir() {
        $path = $this->settings->get_runtime_option( 'awpcp-uploads-dir' );
        return $this->prepare_directory( $path );
    }

    private function prepare_directory( $path ) {
        if ( ! is_dir( $path ) ) {
            return $this->create_directory( $path );
        } else if ( ! is_writable( $path ) ) {
            return $this->make_directory_writable( $path );
        } else {
            return $path;
        }
    }

    private function create_directory( $path ) {
        $previous_umask = umask( 0 );

        if ( ! @mkdir( $path, $this->get_default_directory_mode(), true ) ) {
            $message = __( 'There was a problem trying to create directory <directory-name>.', 'AWPCP' );
            $message = str_replace( '<directory-name>', awpcp_utf8_basename( $path ), $message );
            throw new AWPCP_Exception( $message );
        }

        if ( ! @chown( $path, fileowner( WP_CONTENT_DIR ) ) ) {
            $message = __( 'There was a problem trying to change the owner of <directory-name>.', 'AWPCP' );
            $message = str_replace( '<directory-name>', awpcp_utf8_basename( $path ), $message );
            throw new AWPCP_Exception( $message );
        }

        umask( $previous_umask );

        return $path;
    }

    private function get_default_directory_mode() {
        return intval( $this->settings->get_option( 'upload-directory-permissions', '0755' ), 8 );
    }

    private function make_directory_writable( $path ) {
        // provides fileop class.
        require_once(AWPCP_DIR . '/fileop.class.php');

        $previous_umask = umask( 0 );
        $fileop = new fileop();

        if ( ! $fileop->set_permission( $path, $this->get_default_directory_mode() ) ) {
            $message = __( 'There was a problem trying to make directory <directory-name> writable.', 'AWPCP' );
            $message = str_replace( '<directory-name>', awpcp_utf8_basename( $path ), $message );
            throw new AWPCP_Exception( $message );
        }

        umask( $previous_umask );

        return $path;
    }

    public function get_thumbnails_dir() {
        $path = implode( DIRECTORY_SEPARATOR, array( $this->settings->get_runtime_option( 'awpcp-uploads-dir' ), 'thumbs' ) );
        return $this->prepare_directory( $path );
    }
}
