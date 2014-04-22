<?php

function awpcp_akismet_wrapper_factory() {
    return new AWPCP_AkismetWrapperFactory();
}

class AWPCP_AkismetWrapperFactory {

    public function get_akismet_wrapper() {
        if ( $this->is_akismet_available() ) {
            return new AWPCP_AkismetWrapper();
        } else {
            return new AWPCP_AkismetWrapperBase();
        }
    }

    protected function is_akismet_available() {
        if ( ! class_exists( 'Akismet' ) ) {
            return false;
        }

        $api_key = Akismet::get_api_key();

        if ( empty( $api_key ) ) {
            return false;
        }

        return true;
    }
}
