<?php

function awpcp_upgrade_task_ajax_handler_factory() {
    return new AWPCP_Upgrade_Task_Ajax_Handler_Factory( awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_Upgrade_Task_Ajax_Handler_Factory {

    private $request;
    private $response;

    public function __construct( $request, $response ) {
        $this->request = $request;
        $this->response = $response;
    }

    public function create_upgrade_task_ajax_handler( $tasks_manager ) {
        return new AWPCP_Upgrade_Task_Ajax_Handler( $tasks_manager, $this->request, $this->response );
    }
}
