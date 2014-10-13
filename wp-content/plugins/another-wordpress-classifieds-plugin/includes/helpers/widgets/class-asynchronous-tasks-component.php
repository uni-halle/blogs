<?php

class AWPCP_AsynchronousTasksComponent {

    private $tasks = array();
    private $texts = array();

    public function __construct( $tasks, $texts ) {
        $default_messages = array( 'introduction' => '', 'success' => '', 'button' => '' );

        $this->tasks = $tasks;
        $this->texts = wp_parse_args( $texts, $default_messages );
    }

    public function render() {
        awpcp()->js->set( 'asynchronous-tasks', $this->tasks );
        awpcp()->js->set( 'asynchronous-tasks-texts', $this->texts );

        ob_start();
        # TODO: move template to a top level templates directory
        # templates/components/asynchronous-tasks.tpl.php
        include( AWPCP_DIR . '/admin/templates/asynchronous-tasks.tpl.php' );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
