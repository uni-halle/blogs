<?php

require_once(AWPCP_DIR . '/includes/helpers/admin-page.php');

/**
 * @since 2.1.4
 */
class AWPCP_AdminUpgrade extends AWPCP_AdminPage {

    public function __construct($page=false, $title=false, $menu=false) {
        $page = $page ? $page : 'awpcp-admin-upgrade';
        $title = $title ? $title : awpcp_admin_page_title( _x( 'Manual Upgrade', 'awpcp admin menu', 'another-wordpress-classifieds-plugin' ) );

        parent::__construct($page, $title, $menu);
    }

    public function scripts() {
        wp_enqueue_script( 'awpcp-admin-manual-upgrade' );
    }

    public function dispatch() {
        echo $this->_dispatch();
    }

    private function _dispatch() {
        $pending_upgrades = awpcp()->manual_upgrades->get_pending_tasks();

        $tasks = array();
        foreach ( $pending_upgrades as $action => $data ) {
            $tasks[] = array('name' => $data['name'], 'action' => $action);
        }

        $messages = array(
            'introduction' => _x( 'Before you can use AWPCP again we need to upgrade your database. This operation may take a few minutes, depending on the amount of information stored. Please press the Upgrade button shown below to start the process.', 'awpcp upgrade', 'another-wordpress-classifieds-plugin' ),
            'success' => sprintf( _x( 'Congratulations. AWPCP has been successfully upgraded. You can now access all features. <a href="%s">Click here to Continue</a>.', 'awpcp upgrade', 'another-wordpress-classifieds-plugin' ), add_query_arg( 'page', 'awpcp.php' ) ),
            'button' => _x( 'Upgrade', 'awpcp upgrade', 'another-wordpress-classifieds-plugin' ),
        );

        $tasks = new AWPCP_AsynchronousTasksComponent( $tasks, $messages );

        return $this->render( 'content', $tasks->render() );
    }
}
