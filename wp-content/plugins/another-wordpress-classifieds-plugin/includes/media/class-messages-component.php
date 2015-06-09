<?php

function awpcp_messages_component() {
    return new AWPCP_MessagesComponent( awpcp()->js );
}

class AWPCP_MessagesComponent {

    private $javascript;

    public function __construct( $javascript ) {
        $this->javascript = $javascript;
    }

    public function render( $channels ) {
        $component_id = $this->configure_component( $channels );
        return $this->render_component( $component_id, $channels );
    }

    private function configure_component( $channels ) {
        $component_id = uniqid();

        $this->javascript->set( 'messages-data-for-' . $component_id, array(
            'channels' => $channels
        ) );

        return $component_id;
    }

    private function render_component( $component_id, $channels ) {
        ob_start();
        include( AWPCP_DIR . '/templates/components/messages.tpl.php' );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
