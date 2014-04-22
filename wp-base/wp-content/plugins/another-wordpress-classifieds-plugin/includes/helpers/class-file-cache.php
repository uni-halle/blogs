<?php

function awpcp_file_cache() {
    return new AWPCP_FileCache( WP_CONTENT_DIR . '/uploads/awpcp/cache/' );
}

class AWPCP_FileCache {

    private $location;

    public function __construct( $location ) {
        $this->location = $location;

        if ( ! is_dir( $this->location ) ) {
            mkdir( $this->location, awpcp_directory_permissions(), true );
        }
    }

    public function set( $name, $value ) {
        $filename = $this->path( $name );

        if ( $file = @fopen( $filename, 'w' ) ) {
            fwrite( $file, $value );
            fclose( $file );
        } else {
            throw new AWPCP_IOError( sprintf( "Can't open file %s to write cache entry for '%s'.", $filename, $name ) );
        }
    }

    public function path( $name ) {
        return trailingslashit( $this->location ) . $name . '.json';
    }

    public function get( $name ) {
        $filename = $this->path( $name );

        if ( file_exists( $filename ) && is_readable( $filename ) ) {
            $file = fopen( $filename, 'r' );
            $content = fread( $file, filesize( $filename ) );
            fclose( $file );
        } else {
            throw new AWPCP_Exception( sprintf( "No cache entry found with name '%s'.", $name ) );
        }

        return $content;
    }

    public function url( $name ) {
        return str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $this->path( $name ) );
    }

    public function remove( $name ) {
        $filename = $this->path( $name );

        if ( file_exists( $filename ) && ! @unlink( $filename ) ) {
            throw new AWPCP_IOError( sprintf( "Can't remove %s associated with entry '%s'.", $filename, $name ) );
        }
    }
}
