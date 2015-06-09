<?php

class AWPCP_BackgroundProccess {

    private $pid;
    private $command;
    private $log_file;

    public function __construct( $command, $log_file ) {
        $this->command = $command;
        $this->log_file = $log_file;
    }

    public function start() {
        $command = sprintf( '%s > "%s" 2>&1 & printf "%%u" $!', escapeshellcmd( $this->command ), $this->log_file );
        $this->pid = exec( $command, $output );
    }

    public function get_pid() {
        return $this->pid;
    }

    public function set_pid( $pid ) {
        $this->pid = $pid;
    }

    public function get_command() {
        return $this->command;
    }

    public function get_log_file() {
        return $this->log_file;
    }

    /**
     * How to run a non-blocking command in Windows: proc_close( proc_open( 'start /B ' . $command, array(), $pipes ) );
     */
    // private function is_windows() {
    //     return strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN';
    // }

    /**
     * I'm not currently using the PID to check if process is still running
     * because I haven't found a way to get that value in Windows environments.
     */
    public function is_running() {
        $result = exec( sprintf( 'ps -up %d', $this->pid ), $output );

        if ( ! empty( $result ) && strpos( $result, $this->pid ) ) {
            return true;
        }

        return false;
    }
}
