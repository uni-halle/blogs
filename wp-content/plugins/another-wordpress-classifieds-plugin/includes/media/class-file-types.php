<?php

function awpcp_file_types() {
    static $instance = null;

    if ( is_null( $instance ) ) {
        $instance = new AWPCP_FileTypes( awpcp()->settings );
    }

    return $instance;
}

class AWPCP_FileTypes {

    private $file_types = null;

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function get_file_types() {
        if ( is_null( $this->file_types ) ) {
            $this->file_types = apply_filters( 'awpcp-file-types', $this->get_default_file_types() );
        }

        return $this->file_types;
    }

    private function get_default_file_types() {
        return array(
            'image' => array(
                'png' => array(
                    'name' => 'PNG',
                    'extensions' => array( 'png' ),
                    'mime_types' => array( 'image/png' ),
                ),
                'jpg' => array(
                    'name' => 'JPG',
                    'extensions' => array( 'jpg', 'jpeg', 'pjpeg' ),
                    'mime_types' => array( 'image/jpg', 'image/jpeg', 'image/pjpeg' ),
                ),
                'gif' => array(
                    'name' => 'GIF',
                    'extensions' => array( 'gif' ),
                    'mime_types' => array( 'image/gif' ),
                ),
            )
        );
    }

    public function get_allowed_file_extensions() {
        $extensions = array();

        foreach ( $this->get_file_types() as $group => $_ ) {
            $extensions = array_merge( $extensions, $this->get_allowed_file_extesions_in_group( $group ) );
        }

        return $extensions;
    }

    public function get_file_types_in_group( $group ) {
        return awpcp_array_data( $group, array(), $this->get_file_types() );
    }

    public function get_file_extensions_in_group( $group ) {
        $extensions = array();

        foreach ( $this->get_file_types_in_group( $group ) as $file_type ) {
            $extensions = array_merge( $extensions, $file_type['extensions'] );
        }

        return $extensions;
    }

    public function get_allowed_file_mime_types_in_group( $group ) {
        $file_types = $this->get_file_types_in_group( $group );
        $allowed_extensions = $this->get_allowed_file_extesions_in_group( $group );

        return $this->get_allowed_mime_types( $file_types, $allowed_extensions );
    }

    private function get_allowed_mime_types( $file_types, $allowed_extensions ) {
        $allowed_file_types = array();

        foreach ( $file_types as $file_type ) {
            $common_extensions = array_intersect( $file_type['extensions'], $allowed_extensions );

            if ( ! empty( $common_extensions ) ) {
                $allowed_file_types[] = $file_type;
            }
        }

        return $this->get_mime_types( $allowed_file_types );
    }

    private function get_mime_types( $file_types ) {
        $mime_types = array();

        foreach ( $file_types as $extension => $file_type ) {
            $mime_types = array_merge( $mime_types, $file_type['mime_types'] );
        }

        return $mime_types;
    }

    public function get_allowed_file_extesions_in_group( $group ) {
        switch ( $group ) {
            case 'image':
                $option = 'allowed-image-extensions';
                break;
            case 'video':
                $option = 'attachments-allowed-video-extensions';
                break;
            case 'others':
                $option = 'attachments-allowed-other-files-extensions';
                break;
        }

        return array_values( array_filter( $this->settings->get_option( $option, array() ) ) );
    }

    public function get_file_mime_types_in_group( $group ) {
        return $this->get_mime_types( $this->get_file_types_in_group( $group ) );
    }


    public function get_video_mime_types() {
        return $this->get_mime_types( $this->get_file_types_in_group( 'video' ) );
    }

    public function get_allowed_video_mime_types() {
        return $this->get_allowed_mime_types( $this->get_file_types_in_group( 'video' ), $this->get_allowed_video_extensions() );
    }

    public function get_video_extensions() {
        return $this->get_file_extensions_in_group( 'video' );
    }

    public function get_allowed_video_extensions() {
        return $this->get_allowed_file_extesions_in_group( 'video' );
    }

    public function get_other_files_mime_types() {
        return $this->get_mime_types( $this->get_file_types_in_group( 'others' ) );
    }

    public function get_other_allowed_files_mime_types() {
        return $this->get_allowed_mime_types( $this->get_file_types_in_group( 'others' ), $this->get_other_allowed_files_extensions() );
    }

    public function get_other_files_extensions() {
        return $this->get_file_extensions_in_group( 'others' );
    }

    public function get_other_allowed_files_extensions() {
        return $this->get_allowed_file_extesions_in_group( 'others' );
    }
}
