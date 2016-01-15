<?php

function awpcp_manual_upgrade_tasks_manager() {
    return new AWPCP_Manual_Upgrade_Tasks_Manager();
}

class AWPCP_Manual_Upgrade_Tasks_Manager {

    private $tasks = array();

    public function register_upgrade_task( $slug, $name, $handler ) {
        $this->tasks[ $slug ] = array(
            'name' => $name,
            'handler' => $handler,
        );
    }

    public function get_upgrade_task( $slug ) {
        if ( isset( $this->tasks[ $slug ] ) ) {
            return $this->tasks[ $slug ];
        } else {
            return null;
        }
    }

    public function is_upgrade_task_enabled( $slug ) {
        return get_option( $slug );
    }

    public function has_pending_tasks() {
        foreach ( $this->tasks as $slug => $task ) {
            if ( $this->is_upgrade_task_enabled( $slug ) ) {
                return true;
            }
        }

        return false;
    }

    public function get_pending_tasks() {
        $pending_tasks = array();

        foreach ( $this->tasks as $slug => $task ) {
            if ( $this->is_upgrade_task_enabled( $slug ) ) {
                $pending_tasks[ $slug ] = $task;
            }
        }

        return $pending_tasks;
    }

    public function enable_upgrade_task( $slug ) {
        return update_option( $slug, true );
    }

    public function disable_upgrade_task( $slug ) {
        return delete_option( $slug );
    }
}
