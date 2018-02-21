<?php

interface AWPCP_Plugin_Integration {

    /**
     * Prepares the integration code to affect the plugin's behavour in the way
     * we want.
     */
    public function load();
}
