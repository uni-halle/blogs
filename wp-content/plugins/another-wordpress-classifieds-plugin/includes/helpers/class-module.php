<?php

/**
 * @since 3.3
 */
abstract class AWPCP_Module {

    public $file;
    public $name;
    public $slug;
    public $version;
    public $required_awpcp_version;
    public $textdomain;

    public $notices = array();

    public function __construct( $file, $name, $slug, $version, $required_awpcp_version, $textdomain = null ) {
        $this->file = $file;
        $this->name = $name;
        $this->slug = $slug;
        $this->version = $version;
        $this->required_awpcp_version = $required_awpcp_version;
        $this->textdomain = $textdomain ? $textdomain : "awpcp-{$this->slug}";
    }

    public abstract function required_awpcp_version_notice();

    public function load_textdomain() {
        load_plugin_textdomain( $this->textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    public function setup() {
        if ( ! $this->is_up_to_date() ) {
            $this->install_or_upgrade();
        }

        if ( ! $this->is_up_to_date() ) {
            return;
        }

        $this->module_setup();
    }

    protected function is_up_to_date() {
        $installed_version = $this->get_installed_version();
        return version_compare( $installed_version, $this->version, '==' );
    }

    public function get_installed_version() {
        return $this->version;
    }

    public function install_or_upgrade() {
        // overwrite in children classes if necessary
    }

    protected function module_setup() {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $this->ajax_setup();
        } else if ( is_admin() ) {
            $this->admin_setup();
        } else {
            $this->frontend_setup();
        }
    }

    protected function ajax_setup() {}

    protected function admin_setup() {}

    protected function frontend_setup() {}
}
