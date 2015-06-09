<?php

/**
 * TODO: now that the handler is not defined in the task, this logic object
 * doesn't seem to be necessary anymore. We can work with plain PHP object.
 */
class AWPCP_TaskLogic {

    const TASK_STATUS_NEW = 'new';
    const TASK_STATUS_DELAYED = 'delayed';
    const TASK_STATUS_FAILING = 'failing';
    const TASK_STATUS_FAILED = 'failed';
    const TASK_STATUS_COMPLETE = 'complete';

    private $task;

    public function __construct( $task ) {
        $this->task = $task;
    }

    public function get_id() {
        return $this->task->id;
    }

    public function get_name() {
        return $this->task->name;
    }

    public function get_priority() {
        return $this->task->priority;
    }

    public function get_status() {
        return $this->task->status;
    }

    public function format_status() {
        if ( $this->is_new() ) {
            return _x( 'New', 'task status', 'AWPCP' );
        } else if ( $this->is_delayed() ) {
            return _x( 'Delayed', 'task status', 'AWPCP' );
        } else if ( $this->is_failing() ) {
            return _x( 'Failing', 'task status', 'AWPCP' );
        } else if ( $this->failed() ) {
            return _x( 'Failed', 'task status', 'AWPCP' );
        } else if ( $this->is_complete() ) {
            return _x( 'Complete', 'task status', 'AWPCP' );
        }
    }

    public function format_created_at_date() {
        return awpcp_datetime( 'awpcp', $this->task->created_at );
    }

    public function get_execute_after_date() {
        return $this->task->execute_after;
    }

    public function get_all_metadata() {
        return $this->task->metadata;
    }

    public function get_metadata( $name, $default = null ) {
        if ( isset( $this->task->metadata[ $name ] ) ) {
            $value = $this->task->metadata[ $name ];
        } else {
            $value = $default;
        }

        return $value;
    }

    public function set_metadata( $name, $value ) {
        $this->task->metadata[ $name ] = $value;
    }

    public function delay( $seconds ) {
        $this->set_metadata( 'delay_time', $seconds );

        $this->task->status = self::TASK_STATUS_DELAYED;
        $this->task->execute_after = awpcp_datetime( 'mysql', current_time( 'timestamp' ) + $seconds );
    }

    public function delay_with_decreasing_interval() {
        $five_minutes_in_seconds = 5 * 60;

        $previous_delay = $this->get_metadata( 'delay_time', $five_minutes_in_seconds );
        $next_delay = max( $previous_delay / 2, $five_minutes_in_seconds );

        $this->delay( $next_delay );
    }

    public function retry() {
        $this->task->status = self::TASK_STATUS_FAILING;
        $this->task->priority = $this->task->priority + 1;
    }

    public function fail() {
        $this->task->status = self::TASK_STATUS_FAILED;
        $this->task->priority = $this->task->priority + 1;
    }

    public function complete() {
        $this->task->status = self::TASK_STATUS_COMPLETE;
    }

    public function is_new() {
        return $this->task->status === self::TASK_STATUS_NEW;
    }

    public function is_delayed() {
        return $this->task->status === self::TASK_STATUS_DELAYED;
    }

    public function is_failing() {
        return $this->task->status === self::TASK_STATUS_FAILING;
    }

    public function failed() {
        return $this->task->status === self::TASK_STATUS_FAILED;
    }

    public function is_complete() {
        return $this->task->status === self::TASK_STATUS_COMPLETE;
    }
}
