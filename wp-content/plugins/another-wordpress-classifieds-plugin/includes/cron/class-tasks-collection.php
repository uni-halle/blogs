<?php

function awpcp_tasks_collection() {
    return new AWPCP_TasksCollection( awpcp_task_logic_factory(), $GLOBALS['wpdb'] );
}

class AWPCP_TasksCollection {

    private $task_logic_factory;
    private $db;

    public function __construct( $task_logic_factory, $db ) {
        $this->task_logic_factory = $task_logic_factory;
        $this->db = $db;
    }

    public function create_task( $name, $metadata = array() ) {
        $result = $this->db->insert( AWPCP_TABLE_TASKS, array(
            'name' => $name,
            'execute_after' => current_time( 'mysql' ),
            'metadata' => maybe_serialize( $metadata ),
            'created_at' => current_time( 'mysql' ),
        ) );

        if ( $result === false ) {
            $messsage = __( 'There was an error trying to save the task to the database.', 'AWPCP' );
            throw new AWPCP_Exception( $message );
        }

        return $this->db->insert_id;
    }

    public function get( $task_id ) {
        $sql = 'SELECT * FROM ' . AWPCP_TABLE_TASKS . ' WHERE id = %d';

        $result = $this->db->get_row( $this->db->prepare( $sql, $task_id ) );

        if ( $result === false ) {
            throw new AWPCP_Exception( __( 'There was an error trying to find tasks in the database.', 'AWPCP' ) );
        }

        if ( is_null( $result ) ) {
            $message = __( 'There is no task with ID %d.', 'AWPCP' );
            throw new AWPCP_Exception( sprintf( $message, $task_id ) );
        }

        return $this->create_task_logic_from_result( $result );
    }

    public function get_next_task() {
        $pending_tasks_query = $this->get_tasks_query( array( $this->get_pending_status_condition() ) );
        return $this->get_task_from_query( sprintf( '%s LIMIT 1', $pending_tasks_query ) );
    }

    private function get_tasks_query( $conditions, $order = 'ASC' ) {
        $sql = 'SELECT * FROM ' . AWPCP_TABLE_TASKS . ' WHERE %1$s ORDER BY priority %2$s, execute_after %2$s, created_at %2$s';
        $sql = sprintf( $sql, implode( ' AND ', $conditions ), $order );

        return $sql;
    }

    private function get_pending_status_condition() {
        return "status IN ( 'new', 'delayed', 'failing' )";
    }

    private function get_task_from_query( $query ) {
        $result = $this->db->get_row( $query );

        if ( $result === false ) {
            throw new AWPCP_Exception( 'There was an error tring to retrive the next task from the database.' );
        }

        if ( $result === null ) {
            throw new AWPCP_Exception( 'There are no more tasks.' );
        }

        return $this->create_task_logic_from_result( $result );
    }

    private function create_task_logic_from_result( $task ) {
        $task->metadata = maybe_unserialize( $task->metadata );
        return $this->task_logic_factory->create_task_logic( $task );
    }

    public function get_next_active_task() {
        return $this->get_task_from_query( sprintf( '%s LIMIT 1', $this->get_active_tasks_query() ) );
    }

    private function get_active_tasks_query() {
        return $this->get_tasks_query( array(
            $this->db->prepare( 'execute_after < %s', current_time( 'mysql' ) ),
            $this->get_pending_status_condition(),
        ) );
    }

    public function get_pending_tasks() {
        $pending_tasks_query = $this->get_tasks_query( array( $this->get_pending_status_condition() ), 'DESC' );
        return $this->get_tasks( $pending_tasks_query );
    }

    private function get_tasks( $query ) {
        $results = $this->db->get_results( $query );

        if ( $results === false ) {
            throw new AWPCP_Exception( __( 'There was an error trying to retrive the tasks from the database.', 'awpcp-attachments' ) );
        }

        foreach ( $results as $result ) {
            $tasks[] = $this->create_task_logic_from_result( $result );
        }

        return isset( $tasks ) ? $tasks : array();
    }

    public function count_pending_tasks() {
        return $this->count_tasks( $this->get_count_tasks_query( array( $this->get_pending_status_condition() ) ) );
    }

    private function count_tasks( $query ) {
        $count = $this->db->get_var( $query );

        if ( $count === false ) {
            throw new AWPCP_Exception( __( 'There was an erroy trying to count the tasks in the database.', 'awpcp-attachments' ) );
        }

        return $count;
    }

    private function get_count_tasks_query( $conditions ) {
        $sql = 'SELECT COUNT(*) FROM ' . AWPCP_TABLE_TASKS . ' WHERE %s';
        $sql = sprintf( $sql, implode( ' AND ', $conditions ) );

        return $sql;
    }

    public function get_failed_tasks() {
        return $this->get_tasks( $this->get_tasks_query( array( "status = 'failed'" ), 'DESC' ) );
    }

    public function count_failed_tasks() {
        return $this->count_tasks( $this->get_count_tasks_query( array( "status = 'failed'" ) ) );
    }

    public function get_complete_tasks() {
        return $this->get_tasks( $this->get_tasks_query( array( "status = 'complete'" ), 'DESC' ) );
    }

    public function count_complete_tasks() {
        return $this->count_tasks( $this->get_count_tasks_query( array( "status = 'complete'" ) ) );
    }

    public function update_task( $task ) {
        $data = array(
            'priority' => $task->get_priority(),
            'status' => $task->get_status(),
            'execute_after' => $task->get_execute_after_date(),
            'metadata' => maybe_serialize( $task->get_all_metadata() ),
        );
        $conditions = array( 'id' => $task->get_id() );

        $result = $this->db->update( AWPCP_TABLE_TASKS, $data, $conditions );

        if ( $result === false ) {
            $message = 'There was an error trying to save task <task-id> to the database.';
            throw new AWPCP_Exception( str_replace( '<task-id>', $task->get_id(), $message ) );
        }

        return $result;
    }

    public function delete_task( $task_id ) {
        $result = $this->db->query( $this->db->prepare( 'DELETE FROM ' . AWPCP_TABLE_TASKS . ' WHERE id = %d', $task_id ) );

        if ( $result === false ) {
            $message = 'There was an error trying to delete task <task-id> from the database.';
            throw new AWPCP_Exception( str_replace( '<task-id>', $task_id, $message ) );
        }

        return $result;
    }
}
