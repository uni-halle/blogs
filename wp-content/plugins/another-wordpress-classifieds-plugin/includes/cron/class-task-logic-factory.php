<?php

function awpcp_task_logic_factory() {
    return new AWPCP_TaskLogicFactory();
}

class AWPCP_TaskLogicFactory {

    public function create_task_logic( $task ) {
        return new AWPCP_TaskLogic( $task );
    }
}
