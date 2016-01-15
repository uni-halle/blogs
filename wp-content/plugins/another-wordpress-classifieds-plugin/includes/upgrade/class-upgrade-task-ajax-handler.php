<?php

class AWPCP_Upgrade_Task_Ajax_Handler extends AWPCP_AjaxHandler {

    private $tasks_manager;
    private $request;

    public function __construct( $tasks_manager, $request, $response ) {
        parent::__construct( $response );

        $this->tasks_manager = $tasks_manager;
        $this->request = $request;
    }

    public function ajax() {
        $task_slug = $this->request->param( 'action' );
        $task = $this->tasks_manager->get_upgrade_task( $task_slug );

        if ( is_null( $task ) ) {
            return $this->error_response( sprintf( "No task was found with identifier: %s.", $task_slug ) );
        }

        if ( ! is_callable( $task['handler'] ) ) {
            return $this->error_response( sprintf( "The handler for task '%s' couldn't be instantiated.", $task_slug ) );
        }

        $task_handler = call_user_func( $task['handler'] );

        if ( ! is_object( $task_handler ) ) {
            return $this->error();
        }

        list( $records_count, $records_left ) = $task_handler->run_task();

        if ( $records_left == 0 ) {
            $this->tasks_manager->disable_upgrade_task( $task_slug );
        }

        return $this->progress_response( $records_count, $records_left );
    }
}
