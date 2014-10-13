<?php

require_once(AWPCP_DIR . '/includes/helpers/page.php');


class AWPCP_BasePage extends AWPCP_Page {

    private $request_method = null;
    private $default_step_name = null;
    private $current_step_name = null;
    private $do_next_step = true;
    private $next_step = null;

    protected $request = null;

    public $messages = array();
    public $errors = array();
    public $output = '';

    public function __construct( $steps, $request ) {
        $this->steps = $steps;
        $this->request = $request;
    }

    public function render( $template, $params=array() ) {
        $this->output = parent::render( $template, $params );
    }

    public function dispatch() {
        $this->do_page();
        return $this->output;
    }

    protected function do_page() {
        try {
            $this->do_page_steps();
        } catch (AWPCP_RedirectionException $e) {
            $this->handle_redirection_exception( $e );
        } catch (AWPCP_Exception $e) {
            $this->handle_page_exception( $e );
        }
    }

    protected function do_page_steps() {
        $current_step = $this->get_current_step();
        $this->do_steps( $current_step );
    }

    protected function get_current_step() {
        if ( ! isset( $this->step ) ) {
            $step_name = $this->get_current_step_name();
            $this->step = $this->get_step_by_name( $step_name );
        }

        return $this->step;
    }

    protected function get_current_step_name() {
        if ( ! isset( $this->current_step_name ) ) {
            $this->current_step_name = $this->request->param( 'step', $this->get_default_step_name() );
        }

        return $this->current_step_name;
    }

    private function get_default_step_name() {
        if ( ! isset( $this->default_step_name ) ) {
            $step_names = array_keys( $this->steps );
            $this->default_step_name = reset( $step_names );
        }

        return $this->default_step_name;
    }

    private function get_step_by_name( $step_name ) {
        if ( isset( $this->steps[ $step_name ] ) ) {
            return $this->steps[ $step_name ];
        } else {
            $message = __( 'Unkown step "%s". Please contact the administrator about this error.', 'AWPCP' );
            throw new AWPCP_Exception( sprintf( $message, $step_name ) );
        }
    }

    private function do_steps( $current_step ) {
        try {
            $this->do_step_method( $current_step );
            $this->do_next_step();
        } catch ( AWPCP_RedirectionException $e ) {
            throw $e;
        } catch (AWPCP_Exception $e) {
            $this->handle_step_exception( $e, $current_step );
        }
    }

    private function do_step_method( $step ) {
        switch ( $this->get_request_method() ) {
            case 'POST':
                $step->post( $this );
                break;
            case 'GET':
            default:
                $step->get( $this );
                break;
        }
    }

    private function do_next_step() {
        if ( $this->do_next_step ) {
            $step = $this->get_next_step();
            $step->get( $this );
        }
    }

    private function get_next_step() {
        if ( ! isset( $this->next_step ) ) {
            $current_step = $this->get_current_step();
            $this->next_step = $this->calculate_next_step( $current_step );
        }

        return $this->next_step;
    }

    private function calculate_next_step( $current_step ) {
        throw new AWPCP_Exception( 'Not yet implemented.' );
    }

    private function handle_step_exception( $exception, $step ) {
        if ( $this->request->method() === 'POST' ) {
            $this->errors[] = $exception->getMessage();
            $step->get( $this );
        } else {
            $message = __( 'Your request cannot be processed at this time. Please try again or contact the administrator about the incident.', 'AWPCP' );
            throw new AWPCP_Exception( $message, $exception->get_errors() );
        }
    }

    private function handle_redirection_exception( $redirection ) {
        $this->default_step_name = null;
        $this->current_step_name = null;
        $this->do_next_step = true;
        $this->next_step = null;

        $this->set_request_method( $redirection->request_method );
        $this->set_current_step( $redirection->step_name );

        $this->do_page();
    }

    private function handle_page_exception( $exception ) {
        $this->errors = array_merge( $this->errors, $exception->get_errors() );
        $this->render_page_error();
    }

    protected function render_page_error() {
        $template = AWPCP_DIR . '/frontend/templates/page-error.tpl.php';
        $this->render( $template, array( 'errors' => $this->errors ) );
    }

    public function is_current_step( $step_name ) {
        return strcmp( $this->get_current_step_name(), $step_name ) === 0;
    }

    public function set_current_step( $step_name ) {
        $this->current_step_name = $step_name;
        $this->step = $this->get_step_by_name( $this->current_step_name );
    }

    public function set_next_step( $step_name ) {
        $this->next_step = $this->get_step_by_name( $step_name );
    }

    public function skip_next_step() {
        $this->do_next_step = false;
    }

    /**
     * @since 3.3
     */
    private function get_request_method() {
        if ( is_null( $this->request_method ) ) {
            $this->request_method = $this->request->method();
        }
        return $this->request_method;
    }

    /**
     * @since 3.3
     */
    private function set_request_method( $request_method ) {
        $this->request_method = $request_method;
    }

    public function redirect( $step_name, $request_method='GET' ) {
        if ( strcmp( $this->get_current_step_name(), $step_name ) !== 0 ) {
            throw new AWPCP_RedirectionException( $step_name, $request_method );
        }
    }
}
