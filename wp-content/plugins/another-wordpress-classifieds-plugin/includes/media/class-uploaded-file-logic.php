<?php

class AWPCP_UploadedFileLogic {

    private $file;

    private $settings;

    public function __construct( $file, $settings ) {
        $this->file = $file;
        $this->settings = $settings;
    }

    public function get_mime_type() {
        return $this->file->mime_type;
    }

    public function get_path() {
        return $this->file->path;
    }

    public function get_relative_path() {
        $uploads_dir = $this->settings->get_runtime_option( 'awpcp-uploads-dir' );
        $relative_path = ltrim( str_replace( $uploads_dir, '', $this->get_path() ), DIRECTORY_SEPARATOR );
        return $relative_path;
    }

    public function set_path( $new_path ) {
        $pathinfo = awpcp_utf8_pathinfo( $new_path );

        $this->file->path = $new_path;
        $this->file->name = $pathinfo['basename'];
        $this->file->dirname = $pathinfo['dirname'];
        $this->file->filename = $pathinfo['filename'];
        $this->file->extension = $pathinfo['extension'];
    }

    public function get_parent_directory() {
        return $this->file->dirname;
    }

    public function get_real_name() {
        return $this->file->realname;
    }

    public function get_name() {
        return $this->file->name;
    }

    public function get_file_name() {
        return $this->file->filename;
    }

    public function get_extension() {
        return $this->file->extension;
    }
}
