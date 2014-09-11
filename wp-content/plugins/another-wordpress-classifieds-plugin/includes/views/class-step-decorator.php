<?php

class AWPCP_StepDecorator {
    protected $decorated;

    public function __construct( $decorated ) {
        $this->decorated = $decorated;
    }

    public function get( $controller ) {
        $this->before_get( $controller );
        $this->decorated->get( $controller );
        $this->after_get( $controller );
    }

    protected function before_get( $controller ) {
    }

    protected function after_get( $controller ) {
    }

    public function post( $controller ) {
        $this->before_post( $controller );
        $this->decorated->post( $controller );
        $this->after_post( $controller );
    }

    protected function before_post( $controller ) {
    }

    protected function after_post( $controller ) {
    }
}
