<?php

class AWPCP_SpamFilter {

    private $akismet;
    private $data_source;

    public function __construct( $akismet, $data_source ) {
        $this->akismet = $akismet;
        $this->data_source = $data_source;
    }

    public function is_spam( $subject ) {
        $request_data = $this->get_request_data( $subject );
        $response = $this->akismet->http_post( $request_data, 'comment-check' );
        return $response[1] == 'true';
    }

    protected function get_request_data( $subject ) {
        return http_build_query( array_merge(
            $this->akismet->get_user_data(),
            $this->data_source->get_request_data( $subject )
        ) );
    }
}
