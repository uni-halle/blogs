<?php

function awpcp_uploaded_file_logic_factory() {
    return new AWPCP_UploadedFileLogicFactory( awpcp()->settings );
}

class AWPCP_UploadedFileLogicFactory {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function create_file_logic( $file ) {
        return new AWPCP_UploadedFileLogic( $file, $this->settings );
    }
}
