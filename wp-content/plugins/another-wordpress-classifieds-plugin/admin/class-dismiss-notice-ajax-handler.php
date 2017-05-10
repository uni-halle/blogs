<?php

function awpcp_dismiss_notice_ajax_handler() {
    return new AWPCP_Dismiss_Notice_Ajax_Handler(
        awpcp_request(), awpcp_ajax_response()
    );
}

class AWPCP_Dismiss_Notice_Ajax_Handler extends AWPCP_AjaxHandler {

    private $request;

    public function __construct( $request, $ajax_response ) {
        parent::__construct( $ajax_response );

        $this->request = $request;
    }

    public function ajax() {
        delete_option( 'awpcp-show-' . $this->request->post( 'notice' ) );
        return $this->success();
    }
}
