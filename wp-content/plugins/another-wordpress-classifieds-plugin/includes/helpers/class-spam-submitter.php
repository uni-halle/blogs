<?php

function awpcp_listing_spam_submitter() {
    return new AWPCP_SpamSubmitter( awpcp_akismet_wrapper_factory()->get_akismet_wrapper(), awpcp_listing_akismet_data_source() );
}

class AWPCP_SpamSubmitter {

    private $akismet;
    private $data_source;

    public function __construct( $akismet, $data_source ) {
        $this->akismet = $akismet;
        $this->data_source = $data_source;
    }

    public function submit( $subject ) {
        $request_data = $this->get_request_data( $subject );
        $response = $this->akismet->http_post( $request_data, 'submit-spam' );

        return $response[1] == 'Thanks for making the web a better place.';
    }

    protected function get_request_data( $subject ) {
        return http_build_query( array_merge(
            $this->akismet->get_user_data(),
            $this->akismet->get_reporter_data(),
            $this->data_source->get_request_data( $subject )
        ) );
    }
}
