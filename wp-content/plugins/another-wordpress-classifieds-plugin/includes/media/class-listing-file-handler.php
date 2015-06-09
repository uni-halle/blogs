<?php

abstract class AWPCP_ListingFileHandler {

    protected $validator;
    protected $processor;
    protected $uploads_manager;

    abstract public function can_handle( $file );

    public function handle_file( $listing, $file ) {
        $this->validator->validate_file( $listing, $file );
        $this->move_file( $file );
        $this->processor->process_file( $listing, $file );

        return $file;
    }

    abstract protected function move_file( $file );
}
